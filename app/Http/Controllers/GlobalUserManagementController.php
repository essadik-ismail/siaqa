<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GlobalUserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['tenant', 'agence', 'roles'])
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('saas.global-users.index', compact('users'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $roles = Role::all();
        
        return view('saas.global-users.create', compact('tenants', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'tenant_id' => 'nullable|exists:tenants,id',
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->tenant_id,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->role_id) {
            $user->roles()->attach($request->role_id);
        }

        return redirect()->route('saas.global-users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['tenant', 'agence', 'roles', 'permissions']);
        
        return view('saas.global-users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $tenants = Tenant::all();
        $roles = Role::all();
        
        return view('saas.global-users.edit', compact('user', 'tenants', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'tenant_id' => 'nullable|exists:tenants,id',
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('password', 'password_confirmation');
        
        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Handle role assignment
        if ($request->role_id) {
            $user->roles()->sync([$request->role_id]);
        } else {
            // If no role is selected, remove all roles
            $user->roles()->detach();
        }

        return redirect()->route('saas.global-users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete super administrator.']);
        }

        $user->delete();

        return redirect()->route('saas.global-users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot deactivate super administrator.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.';

        return redirect()->back()->with('success', $message);
    }

    public function launchAsUser(User $user)
    {
        // Check if the user is active
        if (!$user->is_active) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot launch as inactive user.']);
        }

        // Check if the user has a tenant
        if (!$user->tenant) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot launch as user without a tenant.']);
        }

        // Store the current user ID for returning later
        session(['impersonated_by' => auth()->id()]);
        
        // Log out current user and log in as the target user
        auth()->logout();
        auth()->login($user);
        
        // Regenerate session to prevent session fixation
        request()->session()->regenerate();
        
        // Redirect to the user's dashboard
        return redirect()->route('dashboard')
            ->with('success', "Successfully launched as {$user->name}. You can return to your admin account anytime.");
    }
}
