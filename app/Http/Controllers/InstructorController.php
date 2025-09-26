<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instructor\StoreInstructorRequest;
use App\Http\Requests\Instructor\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1; // Default to tenant 1 if not authenticated
        $query = Instructor::with(['user', 'tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        if ($request->has('license_category')) {
            $query->where('license_categories', 'like', '%' . $request->license_category . '%');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $instructors = $query->paginate($perPage);

        return view('instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1; // Default to tenant 1 if not authenticated
        return view('instructors.create', compact('tenantId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get validated data and add tenant_id from authenticated user
            $data = $request->validated();
            $data['tenant_id'] = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;

            $instructor = Instructor::create($data);

            // Load relationships
            $instructor->load(['user', 'tenant']);

            DB::commit();

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create instructor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor): View
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $instructor->load(['user', 'tenant', 'lessons', 'exams', 'studentProgress', 'availability', 'vehicleAssignments']);

        return view('instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor): View
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            abort(404, 'Instructor not found');
        }

        return view('instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $instructor->update($request->validated());
            $instructor->load(['user', 'tenant']);

            DB::commit();

        return redirect()->route('instructors.show', $instructor)
            ->with('success', 'Instructor updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update instructor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Check if instructor has active lessons or exams
            $hasActiveLessons = $instructor->lessons()->whereIn('status', ['scheduled', 'in_progress'])->exists();
            $hasActiveExams = $instructor->exams()->whereIn('status', ['scheduled', 'in_progress'])->exists();

            if ($hasActiveLessons || $hasActiveExams) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete instructor with active lessons or exams'
                ], Response::HTTP_CONFLICT);
            }

            $instructor->delete();

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete instructor: ' . $e->getMessage());
        }
    }

    /**
     * Get instructor's schedule for a specific date range.
     */
    public function schedule(Request $request, Instructor $instructor): View
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            abort(404, 'Instructor not found');
        }

        $startDate = $request->get('start_date', now()->startOfWeek());
        $endDate = $request->get('end_date', now()->endOfWeek());

        $lessons = $instructor->lessons()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->with(['student', 'vehicle'])
            ->orderBy('scheduled_at')
            ->get();

        $exams = $instructor->exams()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->with(['student'])
            ->orderBy('scheduled_at')
            ->get();

        return view('instructors.schedule', compact('instructor', 'lessons', 'exams', 'startDate', 'endDate'));
    }

    /**
     * Get instructor's performance statistics.
     */
    public function performance(Instructor $instructor): View
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            abort(404, 'Instructor not found');
        }

        $stats = [
            'total_lessons' => $instructor->lessons()->count(),
            'completed_lessons' => $instructor->lessons()->where('status', 'completed')->count(),
            'cancelled_lessons' => $instructor->lessons()->where('status', 'cancelled')->count(),
            'total_exams' => $instructor->exams()->count(),
            'passed_exams' => $instructor->exams()->where('status', 'passed')->count(),
            'failed_exams' => $instructor->exams()->where('status', 'failed')->count(),
            'average_rating' => $instructor->lessons()->whereNotNull('student_rating')->avg('student_rating'),
            'total_hours_taught' => $instructor->lessons()->where('status', 'completed')->sum('duration_minutes') / 60,
            'current_students' => $instructor->lessons()->where('status', 'scheduled')->distinct('student_id')->count(),
        ];

        return view('instructors.performance', compact('instructor', 'stats'));
    }

    /**
     * Toggle instructor availability.
     */
    public function toggleAvailability(Instructor $instructor)
    {
        // Check if instructor belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($instructor->tenant_id !== $tenantId) {
            abort(404, 'Instructor not found');
        }

        $instructor->update(['is_available' => !$instructor->is_available]);

        return redirect()->back()
            ->with('success', 'Instructor availability updated successfully');
    }
}
