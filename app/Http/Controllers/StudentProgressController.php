<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentProgress\StoreStudentProgressRequest;
use App\Http\Requests\StudentProgress\UpdateStudentProgressRequest;
use App\Models\StudentProgress;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StudentProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = StudentProgress::with(['student', 'instructor', 'lesson', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('skill_category')) {
            $query->where('skill_category', $request->skill_category);
        }

        if ($request->has('skill_level')) {
            $query->where('skill_level', $request->skill_level);
        }

        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        if ($request->has('is_required')) {
            $query->where('is_required', $request->boolean('is_required'));
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('skill_name', 'like', "%{$search}%")
                  ->orWhere('skill_category', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $progress = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Student progress retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentProgressRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $progress = StudentProgress::create($request->validated());

            // Load relationships
            $progress->load(['student', 'instructor', 'lesson', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $progress,
                'message' => 'Student progress created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student progress',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentProgress $studentProgress): JsonResponse
    {
        // Check if progress belongs to current tenant
        if ($studentProgress->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student progress not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $studentProgress->load(['student', 'instructor', 'lesson', 'tenant']);

        return response()->json([
            'success' => true,
            'data' => $studentProgress,
            'message' => 'Student progress retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentProgressRequest $request, StudentProgress $studentProgress): JsonResponse
    {
        // Check if progress belongs to current tenant
        if ($studentProgress->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student progress not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $studentProgress->update($request->validated());
            $studentProgress->load(['student', 'instructor', 'lesson', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $studentProgress,
                'message' => 'Student progress updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student progress',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentProgress $studentProgress): JsonResponse
    {
        // Check if progress belongs to current tenant
        if ($studentProgress->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student progress not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $studentProgress->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student progress deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student progress',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get progress by student.
     */
    public function byStudent(Student $student): JsonResponse
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $progress = $student->progress()
            ->with(['instructor', 'lesson'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Student progress retrieved successfully'
        ]);
    }

    /**
     * Get progress by skill category.
     */
    public function bySkillCategory(Request $request): JsonResponse
    {
        $request->validate([
            'skill_category' => 'required|string|max:100'
        ]);

        $progress = StudentProgress::with(['student', 'instructor', 'lesson'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('skill_category', $request->skill_category)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Progress by skill category retrieved successfully'
        ]);
    }

    /**
     * Get progress statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = StudentProgress::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_progress_entries' => $query->count(),
            'completed_skills' => $query->where('is_completed', true)->count(),
            'required_skills' => $query->where('is_required', true)->count(),
            'average_success_rate' => $query->avg('success_rate'),
            'total_hours_practiced' => $query->sum('hours_practiced'),
            'by_skill_level' => $query->selectRaw('skill_level, COUNT(*) as count')
                ->groupBy('skill_level')
                ->get(),
            'by_skill_category' => $query->selectRaw('skill_category, COUNT(*) as count')
                ->groupBy('skill_category')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Progress statistics retrieved successfully'
        ]);
    }

    /**
     * Get skill categories.
     */
    public function skillCategories(): JsonResponse
    {
        $categories = StudentProgress::where('tenant_id', auth()->user()->tenant_id)
            ->distinct()
            ->pluck('skill_category')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Skill categories retrieved successfully'
        ]);
    }

    /**
     * Get skills by category.
     */
    public function skillsByCategory(Request $request): JsonResponse
    {
        $request->validate([
            'skill_category' => 'required|string|max:100'
        ]);

        $skills = StudentProgress::where('tenant_id', auth()->user()->tenant_id)
            ->where('skill_category', $request->skill_category)
            ->distinct()
            ->pluck('skill_name')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $skills,
            'message' => 'Skills by category retrieved successfully'
        ]);
    }

    /**
     * Mark skill as completed.
     */
    public function markCompleted(StudentProgress $studentProgress): JsonResponse
    {
        // Check if progress belongs to current tenant
        if ($studentProgress->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student progress not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $studentProgress->update(['is_completed' => true]);

        return response()->json([
            'success' => true,
            'data' => $studentProgress,
            'message' => 'Skill marked as completed'
        ]);
    }

    /**
     * Update skill level.
     */
    public function updateSkillLevel(Request $request, StudentProgress $studentProgress): JsonResponse
    {
        // Check if progress belongs to current tenant
        if ($studentProgress->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student progress not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'skill_level' => 'required|in:beginner,intermediate,advanced,expert'
        ]);

        $studentProgress->update(['skill_level' => $request->skill_level]);

        return response()->json([
            'success' => true,
            'data' => $studentProgress,
            'message' => 'Skill level updated successfully'
        ]);
    }
}
