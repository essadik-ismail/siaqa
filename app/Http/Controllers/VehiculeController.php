<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculeRequest;
use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Agence;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Vehicule::with(['marque', 'agence'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('immatriculation', 'like', "%{$search}%")
                  ->orWhere('couleur', 'like', "%{$search}%")
                  ->orWhereHas('marque', function($marqueQuery) use ($search) {
                      $marqueQuery->where('marque', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        // Apply brand filter
        if ($request->filled('brand')) {
            $query->whereHas('marque', function($marqueQuery) use ($request) {
                $marqueQuery->where('marque', 'like', "%{$request->get('brand')}%");
            });
        }

        // Apply landing display filter
        if ($request->filled('landing_display')) {
            $query->where('landing_display', $request->get('landing_display'));
        }

        $vehicules = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics from ALL vehicles (not filtered) for sidebar
        $tenantId = auth()->user()->tenant_id;
        $totalVehicles = Vehicule::where('tenant_id', $tenantId)->count();
        $availableVehicles = Vehicule::where('tenant_id', $tenantId)->where('statut', 'disponible')->count();
        $onRentalVehicles = Vehicule::where('tenant_id', $tenantId)->where('statut', 'en_location')->count();
        $maintenanceVehicles = Vehicule::where('tenant_id', $tenantId)->where('statut', 'en_maintenance')->count();

        // Get marques for filters
        $marques = Marque::orderBy('marque', 'asc')->get();

        // Get agences for filters
        $agences = Agence::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->orderBy('nom_agence')
            ->get();

        return view('vehicules.index', compact('vehicules', 'marques', 'agences', 'totalVehicles', 'availableVehicles', 'onRentalVehicles', 'maintenanceVehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $agences = Agence::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('nom_agence', 'asc')
            ->get();
        
        return view('vehicules.create', compact('agences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;

        // Handle marque - create or find existing
        $marque = Marque::firstOrCreate(
            [
                'marque' => $data['marque'],
                'tenant_id' => auth()->user()->tenant_id
            ],
            [
                'marque' => $data['marque'],
                'tenant_id' => auth()->user()->tenant_id,
                'is_active' => true
            ]
        );
        $data['marque_id'] = $marque->id;
        unset($data['marque']); // Remove marque from data as it's not a column in vehicules table

        // Handle single image upload
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::storePrivateImage($request->file('image'), 'vehicules');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = ImageHelper::storePrivateImage($image, 'vehicules');
            }
            $data['images'] = $images;
        }

        $vehicule = Vehicule::create($data);

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicule $vehicule): View
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $vehicule->load([
            'marque', 
            'agence', 
            'reservations', 
            'contrats', 
            'assurances',
            'vidanges',
            'visites',
            'interventions'
        ]);

        return view('vehicules.show', compact('vehicule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicule $vehicule): View
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        // Load the vehicle with its relationships
        $vehicule->load(['marque', 'agence']);
        
        $marques = Marque::orderBy('marque', 'asc')->get();
        $agences = Agence::orderBy('nom_agence', 'asc')->get();

        return view('vehicules.edit', compact('vehicule', 'marques', 'agences'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculeRequest $request, Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $data = $request->validated();
        
        \Log::info('Vehicle update data', [
            'vehicle_id' => $vehicule->id,
            'validated_data' => $data,
            'has_image_file' => $request->hasFile('image'),
            'has_images_files' => $request->hasFile('images')
        ]);

        // Handle marque - create or find existing
        if (isset($data['marque'])) {
            $marque = Marque::firstOrCreate(
                [
                    'marque' => $data['marque'],
                    'tenant_id' => auth()->user()->tenant_id
                ],
                [
                    'marque' => $data['marque'],
                    'tenant_id' => auth()->user()->tenant_id,
                    'is_active' => true
                ]
            );
            $data['marque_id'] = $marque->id;
            unset($data['marque']); // Remove marque from data as it's not a column in vehicules table
        }

        // Handle single image upload
        if ($request->hasFile('image')) {
            \Log::info('Image upload detected', [
                'vehicle_id' => $vehicule->id,
                'old_image' => $vehicule->image,
                'new_image_name' => $request->file('image')->getClientOriginalName()
            ]);
            
            // Delete old image if it exists
            if ($vehicule->image && Storage::disk('public')->exists($vehicule->image)) {
                Storage::disk('public')->delete($vehicule->image);
                \Log::info('Old image deleted', ['image_path' => $vehicule->image]);
            }
            
            $data['image'] = $request->file('image')->store('vehicules/images', 'public');
            \Log::info('New image stored', ['new_image_path' => $data['image']]);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($vehicule->images) {
                foreach ($vehicule->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('vehicules/images', 'public');
            }
            $data['images'] = $images;
        }

        \Log::info('Updating vehicle with data', [
            'vehicle_id' => $vehicule->id,
            'final_data' => $data
        ]);
        
        $vehicule->update($data);
        
        \Log::info('Vehicle updated successfully', [
            'vehicle_id' => $vehicule->id,
            'new_image' => $vehicule->fresh()->image
        ]);

        return redirect()->route('vehicules.edit', $vehicule)
            ->with('success', 'Véhicule mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        // Check if vehicule has any related records
        $hasReservations = $vehicule->reservations()->exists();
        $hasContrats = $vehicule->contrats()->exists();
        $hasAssurances = $vehicule->assurances()->exists();
        $hasVidanges = $vehicule->vidanges()->exists();
        $hasVisites = $vehicule->visites()->exists();
        $hasInterventions = $vehicule->interventions()->exists();
        $hasCharges = $vehicule->charges()->exists();

        // Build error message with all related records
        $relatedRecords = [];
        if ($hasReservations) $relatedRecords[] = 'réservations';
        if ($hasContrats) $relatedRecords[] = 'contrats';
        if ($hasAssurances) $relatedRecords[] = 'assurances';
        if ($hasVidanges) $relatedRecords[] = 'vidanges';
        if ($hasVisites) $relatedRecords[] = 'visites';
        if ($hasInterventions) $relatedRecords[] = 'interventions';
        if ($hasCharges) $relatedRecords[] = 'charges';

        if (!empty($relatedRecords)) {
            $recordsText = implode(', ', $relatedRecords);
            return redirect()->route('vehicules.index')
                ->with('error', "Impossible de supprimer ce véhicule car il a des {$recordsText} associés. Veuillez d'abord supprimer ou réassigner ces enregistrements.");
        }

        try {
            // Delete the vehicle image if it exists
            if ($vehicule->image && Storage::disk('public')->exists($vehicule->image)) {
                Storage::disk('public')->delete($vehicule->image);
            }

            $vehicule->delete();

            return redirect()->route('vehicules.index')
                ->with('success', 'Véhicule supprimé avec succès');
        } catch (\Exception $e) {
            \Log::error('Error deleting vehicle', [
                'vehicle_id' => $vehicule->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('vehicules.index')
                ->with('error', 'Erreur lors de la suppression du véhicule. Veuillez réessayer.');
        }
    }

    /**
     * Remove the vehicle's image.
     */
    public function removeImage(Request $request, Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        try {
            \Log::info('Removing image for vehicle', [
                'vehicle_id' => $vehicule->id,
                'current_image' => $vehicule->image,
                'request_action' => $request->input('action'),
                'request_image_path' => $request->input('image_path'),
                'all_request_data' => $request->all()
            ]);

            // Verify this is an image removal request
            if ($request->input('action') !== 'remove_image') {
                \Log::warning('Invalid action for image removal', ['action' => $request->input('action')]);
                return redirect()->route('vehicules.edit', $vehicule)
                    ->with('error', 'Action invalide.');
            }

            // Delete the image file if it exists
            if ($vehicule->image && Storage::disk('public')->exists($vehicule->image)) {
                Storage::disk('public')->delete($vehicule->image);
                \Log::info('Image file deleted from storage', ['image_path' => $vehicule->image]);
            } else {
                \Log::info('No image file to delete or file does not exist', ['image_path' => $vehicule->image]);
            }

            // Remove the image reference from the database
            $vehicule->update(['image' => null]);
            \Log::info('Image reference removed from database');

            return redirect()->route('vehicules.edit', $vehicule)
                ->with('success', 'Image supprimée avec succès. Une image par défaut sera affichée.');
        } catch (\Exception $e) {
            \Log::error('Error removing vehicle image', [
                'vehicle_id' => $vehicule->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('vehicules.edit', $vehicule)
                ->with('error', 'Erreur lors de la suppression de l\'image.');
        }
    }

    /**
     * Display available vehicles.
     */
    public function available(): View
    {
        $vehicules = Vehicule::where('statut', 'disponible')
            ->with(['marque', 'agence'])
            ->orderBy('immatriculation', 'asc')
            ->paginate(15);

        return view('vehicules.available', compact('vehicules'));
    }

    /**
     * Toggle vehicle status.
     */
    public function toggleStatus(Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $vehicule->update(['is_active' => !$vehicule->is_active]);

        $status = $vehicule->is_active ? 'activé' : 'désactivé';
        return redirect()->route('vehicules.index')
            ->with('success', "Véhicule {$status} avec succès");
    }

    /**
     * Toggle landing page display for vehicle.
     */
    public function toggleLandingDisplay(Request $request, Vehicule $vehicule): \Illuminate\Http\JsonResponse
    {
        try {
            // Ensure the vehicule belongs to the current tenant
            if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
                return response()->json(['success' => false, 'message' => 'Vehicle not found'], 404);
            }

            $landingDisplay = $request->input('landing_display', !$vehicule->landing_display);
            
            $vehicule->update(['landing_display' => $landingDisplay]);

            $status = $landingDisplay ? 'shown on' : 'hidden from';
            return response()->json([
                'success' => true, 
                'message' => "Vehicle {$status} landing page successfully",
                'landing_display' => $landingDisplay
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling landing display', [
                'vehicle_id' => $vehicule->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while updating the vehicle'
            ], 500);
        }
    }

    /**
     * Update vehicle status.
     */
    public function updateStatus(Request $request, Vehicule $vehicule): RedirectResponse
    {
        // Ensure the vehicule belongs to the current tenant
        if ($vehicule->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Véhicule non trouvé');
        }

        $request->validate([
            'statut' => 'required|in:disponible,en_maintenance,hors_service,reservé'
        ]);

        $vehicule->update(['statut' => $request->statut]);

        return redirect()->route('vehicules.index')
            ->with('success', 'Statut du véhicule mis à jour avec succès');
    }

    /**
     * Display vehicle statistics.
     */
    public function statistics(): View
    {
        $totalVehicules = Vehicule::count();
        $disponibles = Vehicule::where('statut', 'disponible')->count();
        $enMaintenance = Vehicule::where('statut', 'en_maintenance')->count();
        $horsService = Vehicule::where('statut', 'hors_service')->count();
        $reserves = Vehicule::where('statut', 'reservé')->count();

        $vehiculesByMarque = Vehicule::selectRaw('marques.marque, count(*) as count')
            ->join('marques', 'vehicules.marque_id', '=', 'marques.id')
            ->groupBy('marques.id', 'marques.marque')
            ->orderBy('count', 'desc')
            ->get();

        $vehiculesByAgence = Vehicule::selectRaw('agences.nom_agence, count(*) as count')
            ->join('agences', 'vehicules.agence_id', '=', 'agences.id')
            ->groupBy('agences.id', 'agences.nom_agence')
            ->orderBy('count', 'desc')
            ->get();

        return view('vehicules.statistics', compact(
            'totalVehicules',
            'disponibles',
            'enMaintenance',
            'horsService',
            'reserves',
            'vehiculesByMarque',
            'vehiculesByAgence'
        ));
    }
} 