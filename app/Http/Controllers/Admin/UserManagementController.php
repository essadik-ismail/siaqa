<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Agence;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with(['roles', 'tenant', 'agence'])
            ->orderBy('created_at', 'desc')
            ->get();
        $roles = Role::all();
        $agencies = Agence::all();
        $tenants = Tenant::all();

        return view('admin.users.index', compact('users', 'roles', 'agencies', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $agencies = Agence::all();
        $tenants = Tenant::all();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.users.create', compact('roles', 'agencies', 'tenants', 'recentUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tenant_id' => 'required|exists:tenants,id',
            'agence_id' => 'nullable|exists:agences,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
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
            'phone' => $request->phone,
            'address' => $request->address,
            'tenant_id' => $request->tenant_id,
            'agence_id' => $request->agence_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('roles')) {
            $user->assignRoles($request->roles);
        }

        // Check if user was created from agency users page
        if ($request->has('agency_id') && $request->agency_id) {
            return redirect()->route('admin.agencies.users', $request->agency_id)
                ->with('success', 'User created successfully.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Redirect to users index instead of showing user details
        return redirect()->route('admin.users.index')
            ->with('info', 'User details view has been removed. Use edit to modify user information.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $agencies = Agence::all();
        $tenants = Tenant::all();
        $user->load(['roles']);

        return view('admin.users.edit', compact('user', 'roles', 'agencies', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tenant_id' => 'required|exists:tenants,id',
            'agence_id' => 'nullable|exists:agences,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tenant_id' => $request->tenant_id,
            'agence_id' => $request->agence_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('password') && $request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('roles')) {
            $user->assignRoles($request->roles);
        }

        // Check if user was updated from agency users page
        if ($request->has('agency_id') && $request->agency_id) {
            return redirect()->route('admin.agencies.users', $request->agency_id)
                ->with('success', 'User updated successfully.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete super administrator.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot deactivate super administrator.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')
            ->with('success', "User {$status} successfully.");
    }

    /**
     * Show user permissions
     */
    public function permissions(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);
        $permissions = \App\Models\Permission::all();
        
        return view('admin.users.permissions', compact('user', 'permissions'));
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->assignPermissions($request->permissions ?? []);

        // Check if user permissions were updated from agency users page
        if ($request->has('agency_id') && $request->agency_id) {
            return redirect()->route('admin.agencies.users', $request->agency_id)
                ->with('success', 'User permissions updated successfully.');
        }

        return redirect()->route('admin.users.permissions', $user)
            ->with('success', 'User permissions updated successfully.');
    }

    public function returnFromImpersonation()
    {
        $originalUserId = session('impersonated_by');
        
        if (!$originalUserId) {
            return redirect()->route('dashboard')
                ->with('error', 'No impersonation session found.');
        }

        // Get the original admin user
        $originalUser = User::find($originalUserId);
        
        if (!$originalUser) {
            return redirect()->route('dashboard')
                ->with('error', 'Original admin user not found.');
        }

        // Log out current user and log back in as original admin
        auth()->logout();
        auth()->login($originalUser);
        
        // Clear impersonation session
        session()->forget('impersonated_by');
        
        // Regenerate session
        request()->session()->regenerate();
        
        return redirect()->route('saas.global-users.index')
            ->with('success', 'Successfully returned to admin account.');
    }
}
