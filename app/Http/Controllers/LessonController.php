<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to tenant 1 if not set
        $query = Lesson::with(['student', 'instructor', 'vehicle', 'tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('lesson_type')) {
            $query->where('lesson_type', $request->lesson_type);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
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
                  ->orWhere('lesson_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
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
        $lessons = $query->paginate($perPage);

        return view('lessons.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to tenant 1 if not set
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Instructor::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->with('user:id,name')
            ->get();

        $vehicles = Vehicule::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('marque')
            ->get(['id', 'marque', 'modele']);

        return view('lessons.create', compact('students', 'instructors', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        try {
            DB::beginTransaction();

            $lesson = Lesson::create($request->validated());

            // Load relationships
            $lesson->load(['student', 'instructor', 'vehicle', 'tenant']);

            DB::commit();

            return redirect()->route('lessons.index')
                ->with('success', 'Lesson created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create lesson: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson): View
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        $lesson->load(['student', 'instructor', 'vehicle', 'tenant', 'payments']);

        return view('lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson): View
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        $students = Student::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Instructor::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->with('user:id,name')
            ->get();

        $vehicles = Vehicule::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('marque')
            ->get(['id', 'marque', 'modele']);

        return view('lessons.edit', compact('lesson', 'students', 'instructors', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        try {
            DB::beginTransaction();

            $lesson->update($request->validated());
            $lesson->load(['student', 'instructor', 'vehicle', 'tenant']);

            DB::commit();

        return redirect()->route('lessons.show', $lesson)
            ->with('success', 'Lesson updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update lesson: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        try {
            // Check if lesson is in progress or completed
            if (in_array($lesson->status, ['in_progress', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete lesson that is in progress or completed'
                ], Response::HTTP_CONFLICT);
            }

            $lesson->delete();

        return redirect()->route('lessons.index')
            ->with('success', 'Lesson deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete lesson: ' . $e->getMessage());
        }
    }

    /**
     * Start a lesson.
     */
    public function start(Lesson $lesson)
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        if ($lesson->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled lessons can be started'
            ], Response::HTTP_CONFLICT);
        }

        $lesson->update([
            'status' => 'in_progress',
            'scheduled_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Lesson started successfully');
    }

    /**
     * Complete a lesson.
     */
    public function complete(Request $request, Lesson $lesson)
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        if ($lesson->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Only lessons in progress can be completed'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'instructor_notes' => 'nullable|string|max:1000',
            'student_rating' => 'nullable|integer|min:1|max:5',
            'skills_covered' => 'nullable|array'
        ]);

        $lesson->update([
            'status' => 'completed',
            'completed_at' => now(),
            'instructor_notes' => $request->instructor_notes,
            'student_rating' => $request->student_rating,
            'skills_covered' => $request->skills_covered
        ]);

        return redirect()->back()
            ->with('success', 'Lesson completed successfully');
    }

    /**
     * Cancel a lesson.
     */
    public function cancel(Request $request, Lesson $lesson)
    {
        // Check if lesson belongs to current tenant
        if ($lesson->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Lesson not found');
        }

        if (!in_array($lesson->status, ['scheduled', 'in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled or in-progress lessons can be cancelled'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $lesson->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return redirect()->back()
            ->with('success', 'Lesson cancelled successfully');
    }

    /**
     * Get lessons for a specific date.
     */
    public function byDate(Request $request): View
    {
        $date = $request->get('date', now()->toDateString());
        
        $lessons = Lesson::with(['student', 'instructor', 'vehicle'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at')
            ->get();

        return view('lessons.by-date', compact('lessons', 'date'));
    }

    /**
     * Get available time slots for scheduling.
     */
    public function availableSlots(Request $request): View
    {
        $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:15|max:480'
        ]);

        $instructor = Instructor::findOrFail($request->instructor_id);
        $date = $request->date;
        $duration = $request->duration;

        // Get instructor's availability for the day
        $dayOfWeek = strtolower(now()->parse($date)->format('l'));
        $availability = $instructor->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return view('lessons.available-slots', compact('slots', 'date', 'instructorId', 'vehicleId'))
                ->with('message', 'Instructor not available on this day');
        }

        // Get existing lessons for the instructor on this date
        $existingLessons = Lesson::where('instructor_id', $instructor->id)
            ->whereDate('scheduled_at', $date)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->get();

        // Generate available time slots
        $slots = [];
        $startTime = now()->parse($date . ' ' . $availability->start_time);
        $endTime = now()->parse($date . ' ' . $availability->end_time);

        while ($startTime->addMinutes($duration)->lte($endTime)) {
            $slotStart = $startTime->copy()->subMinutes($duration);
            $slotEnd = $startTime->copy();

            // Check if this slot conflicts with existing lessons
            $conflict = $existingLessons->filter(function ($lesson) use ($slotStart, $slotEnd) {
                $lessonStart = now()->parse($lesson->scheduled_at);
                $lessonEnd = $lessonStart->copy()->addMinutes($lesson->duration_minutes);
                
                return $slotStart->lt($lessonEnd) && $slotEnd->gt($lessonStart);
            })->count() > 0;

            if (!$conflict) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'available' => true
                ];
            }
        }

        return view('lessons.available-slots', compact('slots', 'date', 'instructorId', 'vehicleId'));
    }
}
