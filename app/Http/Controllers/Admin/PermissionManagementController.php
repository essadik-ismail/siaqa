<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permission::with(['roles', 'users', 'tenant'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('display_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->module, function ($query, $module) {
                $query->module($module);
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

        $permissions = $query->paginate(15);
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
        ];
        
        // Get module statistics
        $moduleStats = [];
        foreach ($modules as $module => $displayName) {
            $moduleStats[$module] = Permission::where('module', $module)->count();
        }

        return view('admin.permissions.index', compact('permissions', 'tenants', 'modules', 'moduleStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
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
        ];
        
        return view('admin.permissions.create', compact('tenants', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
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

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $permission->load(['roles', 'users', 'tenant']);
        
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
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
        ];
        
        return view('admin.permissions.edit', compact('permission', 'tenants', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
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

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission assigned to roles.');
        }

        // Check if permission is assigned to users
        if ($permission->users()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission assigned to users.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Show permission roles
     */
    public function roles(Permission $permission)
    {
        $roles = $permission->roles()->with(['users', 'tenant'])->paginate(15);
        
        return view('admin.permissions.roles', compact('permission', 'roles'));
    }

    /**
     * Show permission users
     */
    public function users(Permission $permission)
    {
        $users = $permission->users()->with(['roles', 'tenant', 'agence'])->paginate(15);
        
        return view('admin.permissions.users', compact('permission', 'users'));
    }

    /**
     * Bulk create permissions for a module
     */
    public function bulkCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module' => 'required|string|max:100',
            'tenant_id' => 'nullable|exists:tenants,id',
            'permissions' => 'required|array',
            'permissions.*.name' => 'required|string|max:100',
            'permissions.*.display_name' => 'required|string|max:255',
            'permissions.*.description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $createdCount = 0;
        foreach ($request->permissions as $permData) {
            if (!Permission::where('name', $permData['name'])->exists()) {
                Permission::create([
                    'name' => $permData['name'],
                    'display_name' => $permData['display_name'],
                    'description' => $permData['description'] ?? null,
                    'module' => $request->module,
                    'tenant_id' => $request->tenant_id,
                ]);
                $createdCount++;
            }
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', "{$createdCount} permissions created successfully.");
    }

    /**
     * Show bulk create form
     */
    public function showBulkCreate()
    {
        $tenants = Tenant::all();
        $recentPermissions = Permission::latest()->take(5)->get();
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
        ];
        
        return view('admin.permissions.bulk-create', compact('tenants', 'recentPermissions', 'modules'));
    }
}
