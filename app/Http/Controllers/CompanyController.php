<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
            'company_id' => 'required|integer',
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
            'company_id' => 'required|integer',
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

    // Old Company by email
    public function oldCompanyByEmail($email)
    {
        try {
            $api_url = env('CORE_BASE_URL') . '/get_company_by_email.php/' . $email;
            $response = Http::get($api_url);

            if (!$response->ok()) {
                throw new Exception('Failed to fetch ERP data');
            }

            $companyData = $response->json();
            $company = $companyData['data'] ?? null;

            return response()->json([
                'success' => true,
                'data' => $company
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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

    public function settings()
    {
        // Auth user ka company_id
        $companyId = Auth::user()->company_id;

        // Company ka data fetch karna
        $company = Company::findOrFail($companyId);

        return view('setting', compact('company'));
    }

    // CompanyController.php
    public function updateCompany(Request $request)
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'website' => 'nullable|string|max:255',
        ]);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'updatedby_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Company Information updated successfully.');
    }

    public function updateWhatsApp(Request $request)
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $request->validate([
            'green_api_instance' => 'required|string|max:255',
            'green_api_token' => 'required|string|max:255',
            'green_webhook_url' => 'nullable|url|max:255',
        ]);

        $company->update([
            'green_api_instance' => $request->green_api_instance,
            'green_api_token' => $request->green_api_token,
            'green_webhook_url' => $request->green_webhook_url,
            'updatedby_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'WhatsApp Settings updated successfully.');
    }

    public function updateERP(Request $request)
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $request->validate([
            'erp_system' => 'required|string|max:100',
            'erp_api_base_url' => 'required|url|max:255',
            'erp_api_token' => 'required|string|max:255',
            'erp_auto_sync' => 'required|integer',
        ]);

        $company->update([
            'erp_system' => $request->erp_system,
            'erp_api_base_url' => $request->erp_api_base_url,
            'erp_api_token' => $request->erp_api_token,
            'erp_auto_sync' => $request->erp_auto_sync,
            'updatedby_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'ERP Settings updated successfully.');
    }

    public function updateReminders(Request $request)
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $request->validate([
            'before_due' => 'required|integer',
            'on_due' => 'required|string',
            'after_due_1' => 'required|integer',
            'after_due_2' => 'required|integer',
            'max_reminders' => 'required|integer',
        ]);

        $company->update([
            'before_due' => $request->before_due,
            'on_due' => $request->on_due,
            'after_due_1' => $request->after_due_1,
            'after_due_2' => $request->after_due_2,
            'max_reminders' => $request->max_reminders,
            'updatedby_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Reminder Rules updated successfully.');
    }

    public function toggleWhatsApp($company_id)
    {
        $company = Company::findOrFail($company_id);
        if ($company->green_active == 1) {
            $company->green_active = 0;
        } else {
            $company->green_active = 1;
        }
        $company->updatedby_id = Auth::id();
        $company->update();

        return response()->json(['success' => true]);
    }

    public function toggleERP($company_id)
    {
        $company = Company::findOrFail($company_id);
        if ($company->erp_active == 1) {
            $company->erp_active = 0;
        } else {
            $company->erp_active = 1;
        }
        $company->updatedby_id = Auth::id();
        $company->update();

        return response()->json(['success' => true]);
    }
}
