<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleAssignment\StoreVehicleAssignmentRequest;
use App\Http\Requests\VehicleAssignment\UpdateVehicleAssignmentRequest;
use App\Models\VehicleAssignment;
use App\Models\Vehicule;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VehicleAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = VehicleAssignment::with(['vehicle', 'instructor', 'lesson', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', function ($sq) use ($search) {
                    $sq->where('marque', 'like', "%{$search}%")
                      ->orWhere('modele', 'like', "%{$search}%")
                      ->orWhere('immatriculation', 'like', "%{$search}%");
                })
                ->orWhereHas('instructor', function ($sq) use ($search) {
                    $sq->whereHas('user', function ($usq) use ($search) {
                        $usq->where('name', 'like', "%{$search}%");
                    });
                });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'assigned_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $assignments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'message' => 'Vehicle assignments retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleAssignmentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $assignment = VehicleAssignment::create($request->validated());

            // Load relationships
            $assignment->load(['vehicle', 'instructor', 'lesson', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $assignment,
                'message' => 'Vehicle assignment created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleAssignment $vehicleAssignment): JsonResponse
    {
        // Check if assignment belongs to current tenant
        if ($vehicleAssignment->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $vehicleAssignment->load(['vehicle', 'instructor', 'lesson', 'tenant']);

        return response()->json([
            'success' => true,
            'data' => $vehicleAssignment,
            'message' => 'Vehicle assignment retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleAssignmentRequest $request, VehicleAssignment $vehicleAssignment): JsonResponse
    {
        // Check if assignment belongs to current tenant
        if ($vehicleAssignment->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $vehicleAssignment->update($request->validated());
            $vehicleAssignment->load(['vehicle', 'instructor', 'lesson', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $vehicleAssignment,
                'message' => 'Vehicle assignment updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleAssignment $vehicleAssignment): JsonResponse
    {
        // Check if assignment belongs to current tenant
        if ($vehicleAssignment->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Check if assignment is in use
            if ($vehicleAssignment->status === 'in_use') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vehicle assignment that is in use'
                ], Response::HTTP_CONFLICT);
            }

            $vehicleAssignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle assignment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Assign vehicle to instructor.
     */
    public function assign(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicules,id',
            'instructor_id' => 'required|exists:instructors,id',
            'lesson_id' => 'nullable|exists:lessons,id'
        ]);

        try {
            DB::beginTransaction();

            // Check if vehicle is already assigned
            $existingAssignment = VehicleAssignment::where('vehicle_id', $request->vehicle_id)
                ->where('status', 'in_use')
                ->exists();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is already assigned to another instructor'
                ], Response::HTTP_CONFLICT);
            }

            $assignment = VehicleAssignment::create([
                'tenant_id' => auth()->user()->tenant_id,
                'vehicle_id' => $request->vehicle_id,
                'instructor_id' => $request->instructor_id,
                'lesson_id' => $request->lesson_id,
                'assigned_at' => now(),
                'status' => 'assigned'
            ]);

            $assignment->load(['vehicle', 'instructor', 'lesson', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $assignment,
                'message' => 'Vehicle assigned successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign vehicle',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Return vehicle from instructor.
     */
    public function return(Request $request, VehicleAssignment $vehicleAssignment): JsonResponse
    {
        // Check if assignment belongs to current tenant
        if ($vehicleAssignment->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($vehicleAssignment->status !== 'in_use') {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle is not currently in use'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'odometer_reading_end' => 'nullable|integer|min:0|gte:odometer_reading_start',
            'notes' => 'nullable|string|max:1000'
        ]);

        $vehicleAssignment->update([
            'status' => 'returned',
            'returned_at' => now(),
            'odometer_reading_end' => $request->odometer_reading_end,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'data' => $vehicleAssignment,
            'message' => 'Vehicle returned successfully'
        ]);
    }

    /**
     * Get assignments by vehicle.
     */
    public function byVehicle(Vehicule $vehicle): JsonResponse
    {
        // Check if vehicle belongs to current tenant
        if ($vehicle->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $assignments = $vehicle->assignments()
            ->with(['instructor', 'lesson'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'message' => 'Vehicle assignments retrieved successfully'
        ]);
    }

    /**
     * Get assignments by instructor.
     */
    public function byInstructor(Instructor $instructor): JsonResponse
    {
        // Check if instructor belongs to current tenant
        if ($instructor->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $assignments = $instructor->vehicleAssignments()
            ->with(['vehicle', 'lesson'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'message' => 'Instructor vehicle assignments retrieved successfully'
        ]);
    }

    /**
     * Get current vehicle assignments.
     */
    public function current(Request $request): JsonResponse
    {
        $query = VehicleAssignment::with(['vehicle', 'instructor', 'lesson'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'in_use');

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $assignments = $query->orderBy('assigned_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'message' => 'Current vehicle assignments retrieved successfully'
        ]);
    }

    /**
     * Get assignment statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = VehicleAssignment::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        $stats = [
            'total_assignments' => $query->count(),
            'assigned' => $query->where('status', 'assigned')->count(),
            'in_use' => $query->where('status', 'in_use')->count(),
            'returned' => $query->where('status', 'returned')->count(),
            'damaged' => $query->where('status', 'damaged')->count(),
            'by_vehicle' => $query->with('vehicle')
                ->selectRaw('vehicle_id, COUNT(*) as count')
                ->groupBy('vehicle_id')
                ->get(),
            'by_instructor' => $query->with('instructor')
                ->selectRaw('instructor_id, COUNT(*) as count')
                ->groupBy('instructor_id')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Assignment statistics retrieved successfully'
        ]);
    }
}
