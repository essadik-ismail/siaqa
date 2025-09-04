<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GlobalPermissionManagementController extends Controller
{
    public function index()
    {
        $permissions = Permission::with(['roles', 'users', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.global-permissions.index', compact('permissions'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $modules = [
            'users' => 'User Management',
            'agencies' => 'Agency Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'vehicles' => 'Vehicle Management',
            'clients' => 'Client Management',
            'reservations' => 'Reservation Management',
            'contracts' => 'Contract Management',
            'billing' => 'Billing Management',
            'reports' => 'Reports & Analytics',
            'settings' => 'System Settings',
            'saas' => 'SaaS Management',
        ];
        
        return view('saas.global-permissions.create', compact('tenants', 'modules'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:50',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'module' => $request->module,
            'tenant_id' => $request->tenant_id,
        ]);

        return redirect()->route('saas.global-permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        $permission->load(['roles', 'users', 'tenant']);
        
        return view('saas.global-permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $tenants = Tenant::all();
        $modules = [
            'users' => 'User Management',
            'agencies' => 'Agency Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'vehicles' => 'Vehicle Management',
            'clients' => 'Client Management',
            'reservations' => 'Reservation Management',
            'contracts' => 'Contract Management',
            'billing' => 'Billing Management',
            'reports' => 'Reports & Analytics',
            'settings' => 'System Settings',
            'saas' => 'SaaS Management',
        ];
        
        return view('saas.global-permissions.edit', compact('permission', 'tenants', 'modules'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:50',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'module' => $request->module,
            'tenant_id' => $request->tenant_id,
        ]);

        return redirect()->route('saas.global-permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete permission assigned to roles.']);
        }

        if ($permission->users()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete permission assigned to users.']);
        }

        $permission->delete();

        return redirect()->route('saas.global-permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
