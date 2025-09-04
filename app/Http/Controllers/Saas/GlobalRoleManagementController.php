<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GlobalRoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.global-roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        $tenants = Tenant::all();
        
        return view('saas.global-roles.create', compact('permissions', 'tenants'));
    }

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

        return redirect()->route('saas.global-roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users', 'tenant']);
        
        return view('saas.global-roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $tenants = Tenant::all();
        $role->load(['permissions']);
        
        return view('saas.global-roles.edit', compact('role', 'permissions', 'tenants'));
    }

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

        return redirect()->route('saas.global-roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete super admin role.']);
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete role with assigned users.']);
        }

        $role->delete();

        return redirect()->route('saas.global-roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function permissions(Role $role)
    {
        $role->load(['permissions']);
        $allPermissions = Permission::all()->groupBy('module');
        
        return view('saas.global-roles.permissions', compact('role', 'allPermissions'));
    }

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

        return redirect()->route('saas.global-roles.permissions', $role)
            ->with('success', 'Role permissions updated successfully.');
    }
}
