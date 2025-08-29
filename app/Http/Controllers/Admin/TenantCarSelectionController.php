<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantCarSelectionController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['agences', 'users'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.tenant-car-selection.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['agences', 'users']);
        
        // Get all vehicles for this tenant
        $vehicles = Vehicule::with(['marque', 'agence'])
            ->where('tenant_id', $tenant->id)
            ->orderBy('landing_order')
            ->orderBy('name')
            ->get();

        // Get brands and agencies for this tenant
        $brands = Marque::where('tenant_id', $tenant->id)->orderBy('marque')->get();
        $agencies = Agence::where('tenant_id', $tenant->id)->orderBy('nom_agence')->get();

        return view('admin.tenant-car-selection.show', compact('tenant', 'vehicles', 'brands', 'agencies'));
    }

    public function updateLandingDisplay(Request $request, Tenant $tenant)
    {
        $request->validate([
            'vehicles' => 'required|array',
            'vehicles.*.id' => 'required|exists:vehicules,id',
            'vehicles.*.landing_display' => 'boolean',
            'vehicles.*.landing_order' => 'integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->vehicles as $vehicleData) {
                Vehicule::where('id', $vehicleData['id'])
                    ->where('tenant_id', $tenant->id)
                    ->update([
                        'landing_display' => $vehicleData['landing_display'] ?? false,
                        'landing_order' => $vehicleData['landing_order'] ?? 0,
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Landing page car selection updated successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update landing page car selection: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdate(Request $request, Tenant $tenant)
    {
        $request->validate([
            'action' => 'required|in:show_all,hide_all,reset_order',
            'vehicle_ids' => 'required|array',
            'vehicle_ids.*' => 'exists:vehicules,id',
        ]);

        DB::beginTransaction();

        try {
            switch ($request->action) {
                case 'show_all':
                    Vehicule::whereIn('id', $request->vehicle_ids)
                        ->where('tenant_id', $tenant->id)
                        ->update(['landing_display' => true]);
                    break;

                case 'hide_all':
                    Vehicule::whereIn('id', $request->vehicle_ids)
                        ->where('tenant_id', $tenant->id)
                        ->update(['landing_display' => false]);
                    break;

                case 'reset_order':
                    $vehicles = Vehicule::whereIn('id', $request->vehicle_ids)
                        ->where('tenant_id', $tenant->id)
                        ->orderBy('name')
                        ->get();

                    foreach ($vehicles as $index => $vehicle) {
                        $vehicle->update(['landing_order' => $index + 1]);
                    }
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk update completed successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk update: ' . $e->getMessage()
            ], 500);
        }
    }
}
