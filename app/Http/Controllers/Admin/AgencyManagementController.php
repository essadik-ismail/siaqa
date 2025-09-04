<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AgencyManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agency::with(['tenant', 'users'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom_agence', 'like', "%{$search}%")
                      ->orWhere('adresse', 'like', "%{$search}%")
                      ->orWhere('ville', 'like', "%{$search}%")
                      ->orWhere('rc', 'like', "%{$search}%");
                });
            })
            ->when($request->tenant, function ($query, $tenant) {
                $query->where('tenant_id', $tenant);
            })
            ->when($request->status !== null && $request->status !== '', function ($query, $status) {
                $query->where('is_active', $request->status == '1');
            })
            ->orderBy('created_at', 'desc');

        $agencies = $query->paginate(15);
        $tenants = Tenant::all();

        return view('admin.agencies.index', compact('agencies', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = Tenant::all();
        $recentAgencies = Agency::latest()->take(5)->get();
        
        return view('admin.agencies.create', compact('tenants', 'recentAgencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_agence' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'rc' => 'nullable|string|max:100',
            'patente' => 'nullable|string|max:100',
            'IF' => 'nullable|string|max:100',
            'n_cnss' => 'nullable|string|max:100',
            'ICE' => 'nullable|string|max:100',
            'n_compte_bancaire' => 'nullable|string|max:100',
            'tenant_id' => 'required|exists:tenants,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agencies/logos', 'public');
            $data['logo'] = $logoPath;
        }

        Agency::create($data);

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agency created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
        $agency->load(['tenant', 'users']);
        
        return view('admin.agencies.show', compact('agency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agency $agency)
    {
        $tenants = Tenant::all();
        
        return view('admin.agencies.edit', compact('agency', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agency $agency)
    {
        $validator = Validator::make($request->all(), [
            'nom_agence' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'rc' => 'nullable|string|max:100',
            'patente' => 'nullable|string|max:100',
            'IF' => 'nullable|string|max:100',
            'n_cnss' => 'nullable|string|max:100',
            'ICE' => 'nullable|string|max:100',
            'n_compte_bancaire' => 'nullable|string|max:100',
            'tenant_id' => 'required|exists:tenants,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($agency->logo && Storage::disk('public')->exists($agency->logo)) {
                Storage::disk('public')->delete($agency->logo);
            }
            
            $logoPath = $request->file('logo')->store('agencies/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $agency->update($data);

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agency updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agency $agency)
    {
        // Check if agency has users
        if ($agency->users()->count() > 0) {
            return redirect()->route('admin.agencies.index')
                ->with('error', 'Cannot delete agency with assigned users.');
        }

        // Delete logo if exists
        if ($agency->logo && Storage::disk('public')->exists($agency->logo)) {
            Storage::disk('public')->delete($agency->logo);
        }

        $agency->delete();

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agency deleted successfully.');
    }

    /**
     * Toggle agency active status
     */
    public function toggleStatus(Agency $agency)
    {
        $agency->update(['is_active' => !$agency->is_active]);

        $status = $agency->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.agencies.index')
            ->with('success', "Agency {$status} successfully.");
    }

    /**
     * Show agency users
     */
    public function users(Agency $agency)
    {
        $users = $agency->users()->with(['roles', 'tenant'])->paginate(15);
        
        return view('admin.agencies.users', compact('agency', 'users'));
    }

    /**
     * Show agency statistics
     */
    public function statistics(Agency $agency)
    {
        $stats = [
            'total_users' => $agency->users()->count(),
            'active_users' => $agency->users()->where('is_active', true)->count(),
            'total_vehicles' => $agency->vehicles()->count(),
            'total_reservations' => $agency->reservations()->count(),
        ];
        
        return view('admin.agencies.statistics', compact('agency', 'stats'));
    }
}
