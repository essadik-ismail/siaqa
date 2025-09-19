<?php

namespace App\Http\Controllers;

use App\Http\Requests\TheoryClass\StoreTheoryClassRequest;
use App\Http\Requests\TheoryClass\UpdateTheoryClassRequest;
use App\Models\TheoryClass;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TheoryClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = TheoryClass::with(['instructor', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('license_category')) {
            $query->where('license_category', $request->license_category);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('class_number', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function ($sq) use ($search) {
                      $sq->whereHas('user', function ($usq) use ($search) {
                          $usq->where('name', 'like', "%{$search}%");
                      });
                  });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'scheduled_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $classes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $classes,
            'message' => 'Theory classes retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTheoryClassRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $class = TheoryClass::create($request->validated());

            // Load relationships
            $class->load(['instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $class,
                'message' => 'Theory class created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create theory class',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $theoryClass->load(['instructor', 'tenant', 'enrollments.student']);

        return response()->json([
            'success' => true,
            'data' => $theoryClass,
            'message' => 'Theory class retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTheoryClassRequest $request, TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $theoryClass->update($request->validated());
            $theoryClass->load(['instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $theoryClass,
                'message' => 'Theory class updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update theory class',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Check if class is in progress or completed
            if (in_array($theoryClass->status, ['in_progress', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete class that is in progress or completed'
                ], Response::HTTP_CONFLICT);
            }

            $theoryClass->delete();

            return response()->json([
                'success' => true,
                'message' => 'Theory class deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete theory class',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Start a theory class.
     */
    public function start(TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($theoryClass->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled classes can be started'
            ], Response::HTTP_CONFLICT);
        }

        $theoryClass->update([
            'status' => 'in_progress',
            'scheduled_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $theoryClass,
            'message' => 'Theory class started successfully'
        ]);
    }

    /**
     * Complete a theory class.
     */
    public function complete(Request $request, TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($theoryClass->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Only classes in progress can be completed'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'instructor_notes' => 'nullable|string|max:1000'
        ]);

        $theoryClass->update([
            'status' => 'completed',
            'completed_at' => now(),
            'instructor_notes' => $request->instructor_notes
        ]);

        return response()->json([
            'success' => true,
            'data' => $theoryClass,
            'message' => 'Theory class completed successfully'
        ]);
    }

    /**
     * Cancel a theory class.
     */
    public function cancel(Request $request, TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!in_array($theoryClass->status, ['scheduled', 'in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled or in-progress classes can be cancelled'
            ], Response::HTTP_CONFLICT);
        }

        $theoryClass->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'data' => $theoryClass,
            'message' => 'Theory class cancelled successfully'
        ]);
    }

    /**
     * Enroll a student in the theory class.
     */
    public function enroll(Request $request, TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        if (!$theoryClass->hasAvailableSpots()) {
            return response()->json([
                'success' => false,
                'message' => 'Class is full'
            ], Response::HTTP_CONFLICT);
        }

        $success = $theoryClass->enrollStudent($request->student_id);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll student. Student may already be enrolled or class is full.'
            ], Response::HTTP_CONFLICT);
        }

        return response()->json([
            'success' => true,
            'data' => $theoryClass->fresh(['enrollments.student']),
            'message' => 'Student enrolled successfully'
        ]);
    }

    /**
     * Unenroll a student from the theory class.
     */
    public function unenroll(Request $request, TheoryClass $theoryClass): JsonResponse
    {
        // Check if class belongs to current tenant
        if ($theoryClass->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Theory class not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        $success = $theoryClass->unenrollStudent($request->student_id);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not enrolled in this class'
            ], Response::HTTP_CONFLICT);
        }

        return response()->json([
            'success' => true,
            'data' => $theoryClass->fresh(['enrollments.student']),
            'message' => 'Student unenrolled successfully'
        ]);
    }

    /**
     * Get classes for a specific date.
     */
    public function byDate(Request $request): JsonResponse
    {
        $date = $request->get('date', now()->toDateString());
        
        $classes = TheoryClass::with(['instructor'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes,
            'message' => 'Theory classes for date retrieved successfully'
        ]);
    }

    /**
     * Get available classes for enrollment.
     */
    public function available(Request $request): JsonResponse
    {
        $query = TheoryClass::with(['instructor'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->whereRaw('current_students < max_students');

        if ($request->has('license_category')) {
            $query->where('license_category', $request->license_category);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $classes = $query->orderBy('scheduled_at')->get();

        return response()->json([
            'success' => true,
            'data' => $classes,
            'message' => 'Available theory classes retrieved successfully'
        ]);
    }
}
