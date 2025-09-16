<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::with(['permissions', 'users', 'tenant'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('display_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->tenant, function ($query, $tenant) {
                $query->where('tenant_id', $tenant);
            })
            ->when($request->type, function ($query, $type) {
                if ($type === 'system') {
                    $query->system();
                } elseif ($type === 'tenant') {
                    $query->tenant();
                }
            })
            ->orderBy('created_at', 'desc');

        $roles = $query->paginate(15);
        $tenants = Tenant::all();

        return view('admin.roles.index', compact('roles', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        $tenants = Tenant::all();
        $recentRoles = Role::latest()->take(5)->get();
        
        return view('admin.roles.create', compact('permissions', 'tenants', 'recentRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'tenant_id' => 'nullable|exists:tenants,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'tenant_id' => $request->tenant_id,
        ]);

        if ($request->has('permissions')) {
            $role->assignPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users', 'tenant']);
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $tenants = Tenant::all();
        $role->load(['permissions']);
        
        return view('admin.roles.edit', compact('role', 'permissions', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'tenant_id' => 'nullable|exists:tenants,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'tenant_id' => $request->tenant_id,
        ]);

        if ($request->has('permissions')) {
            $role->assignPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users.');
        }

        // Check if it's a system role
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete super admin role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show role permissions
     */
    public function permissions(Role $role)
    {
        $role->load(['permissions']);
        $allPermissions = Permission::all()->groupBy('module');
        
        return view('admin.roles.permissions', compact('role', 'allPermissions'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
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

        $role->assignPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.permissions', $role)
            ->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Duplicate role
     */
    public function duplicate(Role $role)
    {
        $newRole = $role->replicate();
        $newRole->name = $role->name . '_copy';
        $newRole->display_name = $role->display_name . ' (Copy)';
        $newRole->save();

        // Copy permissions
        $permissionIds = $role->permissions()->pluck('permissions.id')->toArray();
        $newRole->assignPermissions($permissionIds);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role duplicated successfully.');
    }

    /**
     * Show role users
     */
    public function users(Role $role)
    {
        $users = $role->users()->with(['tenant', 'agence'])->paginate(15);
        
        return view('admin.roles.users', compact('role', 'users'));
    }
}
