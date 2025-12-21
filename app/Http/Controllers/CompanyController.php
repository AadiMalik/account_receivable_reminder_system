<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();
        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'required|string|max:20',
            'company_id' => 'required|integer|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
            'createdby_id' => Auth::user()->id,
        ]);

        // Create company user
        $user = User::create([
            'name' => $request->name . ' User',
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => 2, // Company Role
            'password' => Hash::make('12345678'), // default password
            'company_id' => $company->id,
            'createdby_id' => Auth::user()->id,
        ]);
        $company->login_user_id = $user->id;
        $company->save();

        return response()->json(['success' => 'Company created successfully!', 'company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $id,
            'phone' => 'required|string|max:20',
            'company_id' => 'required|integer|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
            'updatedby_id' => Auth::user()->id,
        ]);
        $user = User::where('company_id', $company->login_user_id)->first();
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'updatedby_id' => Auth::user()->id,
            ]);
        }

        return response()->json(['success' => 'Company updated successfully!', 'company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['success' => 'Company deleted successfully!']);
    }

    public function loginAsCompany($companyId)
    {
        $company = Company::findOrFail($companyId);
        if (!$company->login_user_id) {
            return redirect()->back()->with('error', 'No user associated with this company.');
        }

        $user = User::find($company->login_user_id);

        if ($user) {
            // Store current admin ID in session (only if not already stored)
            if (!session()->has('admin_user_id')) {
                session(['admin_user_id' => Auth::user()->id]);
            }

            // Login as company user
            Auth::login($user);

            return redirect('dashboard'); // company dashboard route
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    // Restore admin login
    public function restoreAdmin()
    {
        $adminId = session('admin_user_id');

        if ($adminId) {
            $adminUser = User::find($adminId);

            if ($adminUser) {
                Auth::login($adminUser);
                session()->forget('admin_user_id'); // remove after restore
                return redirect('company'); // admin dashboard route
            }
        }

        return redirect('/login')->with('error', 'Unable to restore admin session.');
    }
}
