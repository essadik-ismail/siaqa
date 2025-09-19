<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Student::with(['user', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('license_category')) {
            $query->where('license_category', $request->license_category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $students = $query->paginate($perPage);

        return view('students.index', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $student = Student::create($request->validated());

            // Load relationships
            $student->load(['user', 'tenant']);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Étudiant créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'étudiant: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        $student->load([
            'user', 
            'tenant', 
            'lessons', 
            'exams', 
            'payments', 
            'progress', 
            'studentPackages',
            'theoryEnrollments'
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        try {
            DB::beginTransaction();

            $student->update($request->validated());
            $student->load(['user', 'tenant']);

            DB::commit();

            return redirect()->route('students.show', $student)
                ->with('success', 'Étudiant mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'étudiant: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        try {
            // Check if student has active lessons or exams
            $hasActiveLessons = $student->lessons()->whereIn('status', ['scheduled', 'in_progress'])->exists();
            $hasActiveExams = $student->exams()->whereIn('status', ['scheduled', 'in_progress'])->exists();

            if ($hasActiveLessons || $hasActiveExams) {
                return redirect()->back()
                    ->with('error', 'Cannot delete student with active lessons or exams');
            }

            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }

    /**
     * Get student's progress summary.
     */
    public function progress(Student $student): View
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        $progress = [
            'theory_hours' => [
                'completed' => $student->theory_hours_completed,
                'required' => $student->required_theory_hours,
                'percentage' => $student->theory_completion_percentage
            ],
            'practical_hours' => [
                'completed' => $student->practical_hours_completed,
                'required' => $student->required_practical_hours,
                'percentage' => $student->practical_completion_percentage
            ],
            'lessons' => [
                'total' => $student->lessons()->count(),
                'completed' => $student->lessons()->where('status', 'completed')->count(),
                'scheduled' => $student->lessons()->where('status', 'scheduled')->count(),
                'cancelled' => $student->lessons()->where('status', 'cancelled')->count()
            ],
            'exams' => [
                'total' => $student->exams()->count(),
                'passed' => $student->exams()->where('status', 'passed')->count(),
                'failed' => $student->exams()->where('status', 'failed')->count(),
                'scheduled' => $student->exams()->where('status', 'scheduled')->count()
            ],
            'payments' => [
                'total_due' => $student->total_due,
                'total_paid' => $student->total_paid,
                'balance' => $student->total_due - $student->total_paid
            ]
        ];

        return view('students.progress', compact('student', 'progress'));
    }

    /**
     * Get student's schedule.
     */
    public function schedule(Request $request, Student $student): View
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        $startDate = $request->get('start_date', now()->startOfWeek());
        $endDate = $request->get('end_date', now()->endOfWeek());

        $lessons = $student->lessons()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->with(['instructor', 'vehicle'])
            ->orderBy('scheduled_at')
            ->get();

        $exams = $student->exams()
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->with(['instructor'])
            ->orderBy('scheduled_at')
            ->get();

        return view('students.schedule', compact('student', 'lessons', 'exams', 'startDate', 'endDate'));
    }

    /**
     * Update student status.
     */
    public function updateStatus(Request $request, Student $student)
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        $request->validate([
            'status' => 'required|in:registered,active,suspended,graduated,dropped'
        ]);

        $student->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Student status updated successfully');
    }

    /**
     * Get student's payment history.
     */
    public function payments(Student $student): View
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Student not found');
        }

        $payments = $student->payments()
            ->with(['lesson', 'exam'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('students.payments', compact('student', 'payments'));
    }
}
