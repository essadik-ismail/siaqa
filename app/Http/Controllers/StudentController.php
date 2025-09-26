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
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1; // Default to tenant 1 if not authenticated
        $query = Student::with(['user', 'tenant'])
            ->where('tenant_id', $tenantId);

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

            // Get validated data and add tenant_id from authenticated user
            $data = $request->validated();
            $data['tenant_id'] = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;

            // Handle file uploads
            if ($request->hasFile('cinimage')) {
                $cinImage = $request->file('cinimage');
                $cinImageName = time() . '_cin_' . $cinImage->getClientOriginalName();
                $cinImage->storeAs('public/students/cin', $cinImageName);
                $data['cinimage'] = 'students/cin/' . $cinImageName;
            } else {
                $data['cinimage'] = null; // Set to null if no file uploaded
            }

            if ($request->hasFile('image')) {
                $profileImage = $request->file('image');
                $profileImageName = time() . '_profile_' . $profileImage->getClientOriginalName();
                $profileImage->storeAs('public/students/profile', $profileImageName);
                $data['image'] = 'students/profile/' . $profileImageName;
            } else {
                $data['image'] = null; // Set to null if no file uploaded
            }

            $student = Student::create($data);

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
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1; // Default to tenant 1 if not authenticated
        return view('students.create', compact('tenantId'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        // Check if student belongs to current tenant
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
            abort(404, 'Student not found');
        }

        $student->load([
            'user', 
            'tenant',
            'lessons' => function($query) {
                $query->with(['instructor.user', 'vehicule']);
            },
            'exams' => function($query) {
                $query->with(['instructor.user']);
            },
            'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'progress',
            'studentPackages'
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        // Check if student belongs to current tenant
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
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
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
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
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
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
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
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
     * Update student status.
     */
    public function updateStatus(Request $request, Student $student)
    {
        // Check if student belongs to current tenant
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
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
        $currentTenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $currentTenantId) {
            abort(404, 'Student not found');
        }

        $payments = $student->payments()
            ->with(['lesson', 'exam'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('students.payments', compact('student', 'payments'));
    }

    /**
     * Toggle blacklist status of a student.
     */
    public function toggleBlacklist(Student $student): RedirectResponse
    {
        // Ensure the student belongs to the current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $tenantId) {
            abort(404, 'Student not found');
        }

        $student->update(['is_blacklisted' => !$student->is_blacklisted]);

        $status = $student->is_blacklisted ? 'blacklisted' : 'removed from blacklist';
        return redirect()->back()->with('success', "Student {$status} successfully.");
    }

    /**
     * Display student statistics.
     */
    public function statistics(): View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        
        $totalStudents = Student::where('tenant_id', $tenantId)->count();
        $activeStudents = Student::where('tenant_id', $tenantId)->where('is_blacklisted', false)->count();
        $blacklistedStudents = Student::where('tenant_id', $tenantId)->where('is_blacklisted', true)->count();
        
        // Recent registrations (last 30 days)
        $recentRegistrations = Student::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        // Students by status
        $studentsByStatus = Student::where('tenant_id', $tenantId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $stats = [
            'total' => $totalStudents,
            'active' => $activeStudents,
            'blacklisted' => $blacklistedStudents,
            'recent_registrations' => $recentRegistrations,
            'by_status' => $studentsByStatus
        ];

        return view('students.statistics', compact('stats'));
    }

    /**
     * Search students.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        
        $students = Student::where('tenant_id', $tenantId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('students.search', compact('students', 'query'));
    }
}
