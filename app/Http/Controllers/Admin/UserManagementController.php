<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Agency;
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
        $query = User::with(['roles', 'tenant', 'agency'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('id', $role);
                });
            })
            ->when($request->status !== null && $request->status !== '', function ($query, $status) {
                $query->where('is_active', $request->status == '1');
            })
            ->when($request->agency, function ($query, $agency) {
                $query->where('agency_id', $agency);
            });

        $users = $query->paginate(15);
        $roles = Role::all();
        $agencies = Agency::all();
        $tenants = Tenant::all();

        return view('admin.users.index', compact('users', 'roles', 'agencies', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $agencies = Agency::all();
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
            'agency_id' => 'nullable|exists:agencies,id',
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
            'agency_id' => $request->agency_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('roles')) {
            $user->assignRoles($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'permissions', 'tenant', 'agency']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $agencies = Agency::all();
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
            'agency_id' => 'nullable|exists:agencies,id',
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
            'agency_id' => $request->agency_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('password') && $request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('roles')) {
            $user->assignRoles($request->roles);
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
        $allPermissions = \App\Models\Permission::all()->groupBy('module');
        
        return view('admin.users.permissions', compact('user', 'allPermissions'));
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

        return redirect()->route('admin.users.permissions', $user)
            ->with('success', 'User permissions updated successfully.');
    }
}
