<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructorAvailability\StoreInstructorAvailabilityRequest;
use App\Http\Requests\InstructorAvailability\UpdateInstructorAvailabilityRequest;
use App\Models\InstructorAvailability;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class InstructorAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = InstructorAvailability::with(['instructor', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('instructor', function ($q) use ($search) {
                $q->whereHas('user', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'day_of_week');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $availability = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Instructor availability retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorAvailabilityRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $availability = InstructorAvailability::create($request->validated());

            // Load relationships
            $availability->load(['instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $availability,
                'message' => 'Instructor availability created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create instructor availability',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InstructorAvailability $instructorAvailability): JsonResponse
    {
        // Check if availability belongs to current tenant
        if ($instructorAvailability->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor availability not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $instructorAvailability->load(['instructor', 'tenant']);

        return response()->json([
            'success' => true,
            'data' => $instructorAvailability,
            'message' => 'Instructor availability retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorAvailabilityRequest $request, InstructorAvailability $instructorAvailability): JsonResponse
    {
        // Check if availability belongs to current tenant
        if ($instructorAvailability->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor availability not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $instructorAvailability->update($request->validated());
            $instructorAvailability->load(['instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $instructorAvailability,
                'message' => 'Instructor availability updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update instructor availability',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstructorAvailability $instructorAvailability): JsonResponse
    {
        // Check if availability belongs to current tenant
        if ($instructorAvailability->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor availability not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $instructorAvailability->delete();

            return response()->json([
                'success' => true,
                'message' => 'Instructor availability deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete instructor availability',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get availability by instructor.
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

        $availability = $instructor->availability()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Instructor availability retrieved successfully'
        ]);
    }

    /**
     * Get availability by day of week.
     */
    public function byDay(Request $request): JsonResponse
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'
        ]);

        $availability = InstructorAvailability::with(['instructor'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Availability by day retrieved successfully'
        ]);
    }

    /**
     * Get available instructors for a specific time slot.
     */
    public function availableInstructors(Request $request): JsonResponse
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        $dayOfWeek = $request->day_of_week;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $availableInstructors = InstructorAvailability::with(['instructor'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableInstructors,
            'message' => 'Available instructors retrieved successfully'
        ]);
    }

    /**
     * Toggle availability status.
     */
    public function toggleAvailability(InstructorAvailability $instructorAvailability): JsonResponse
    {
        // Check if availability belongs to current tenant
        if ($instructorAvailability->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor availability not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $instructorAvailability->update(['is_available' => !$instructorAvailability->is_available]);

        return response()->json([
            'success' => true,
            'data' => $instructorAvailability,
            'message' => 'Availability status updated successfully'
        ]);
    }

    /**
     * Get availability statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = InstructorAvailability::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $stats = [
            'total_availability_slots' => $query->count(),
            'available_slots' => $query->where('is_available', true)->count(),
            'unavailable_slots' => $query->where('is_available', false)->count(),
            'by_day' => $query->selectRaw('day_of_week, COUNT(*) as count')
                ->groupBy('day_of_week')
                ->get(),
            'by_instructor' => $query->with('instructor')
                ->selectRaw('instructor_id, COUNT(*) as count')
                ->groupBy('instructor_id')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Availability statistics retrieved successfully'
        ]);
    }

    /**
     * Get weekly schedule for an instructor.
     */
    public function weeklySchedule(Instructor $instructor): JsonResponse
    {
        // Check if instructor belongs to current tenant
        if ($instructor->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $schedule = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            $dayAvailability = $instructor->availability()
                ->where('day_of_week', $day)
                ->where('is_available', true)
                ->orderBy('start_time')
                ->get();

            $schedule[$day] = $dayAvailability;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'instructor' => $instructor,
                'schedule' => $schedule
            ],
            'message' => 'Weekly schedule retrieved successfully'
        ]);
    }
}
