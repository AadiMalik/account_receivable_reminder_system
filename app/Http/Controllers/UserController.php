<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Display list of users
    public function index()
    {
        $users = User::orderBy('name')->where('company_id', auth()->user()->company_id)->get();
        return view('users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => 2,
            'password' => Hash::make($request->password),
            'company_id' => auth()->user()->company_id,
            'createdby_id' => auth()->user()->id
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    // Show edit form
    public function edit(User $user)
    {
        return view('users.create', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->company_id = auth()->user()->company_id;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->updatedby_id = auth()->user()->id;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
