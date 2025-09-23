<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to tenant 1 if not set
        $query = Exam::with(['student', 'instructor', 'tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->has('license_category')) {
            $query->where('license_category', $request->license_category);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
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
                $q->where('exam_number', 'like', "%{$search}%")
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
        $exams = $query->paginate($perPage);

        // Get filter options
        $students = Student::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Instructor::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('user.name')
            ->with('user:id,name')
            ->get();

        return view('exams.index', compact('exams', 'students', 'instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Get validated data and add tenant_id from authenticated user
            $data = $request->validated();
            $data['tenant_id'] = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;

            $exam = Exam::create($data);

            // Load relationships
            $exam->load(['student', 'instructor', 'tenant']);

            DB::commit();

            return redirect()->route('exams.index')
                ->with('success', 'Examen créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'examen: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\View\View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1; // Default to tenant 1 if not authenticated
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Instructor::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->with('user:id,name')
            ->get();

        return view('exams.create', compact('students', 'instructors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam): \Illuminate\View\View
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Exam not found');
        }

        $exam->load(['student', 'instructor', 'tenant', 'payments']);

        return view('exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam): \Illuminate\View\View
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Exam not found');
        }

        $students = Student::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $instructors = Instructor::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->with('user:id,name')
            ->get();

        return view('exams.edit', compact('exam', 'students', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamRequest $request, Exam $exam): \Illuminate\Http\RedirectResponse
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Exam not found');
        }

        try {
            DB::beginTransaction();

            $exam->update($request->validated());
            $exam->load(['student', 'instructor', 'tenant']);

            DB::commit();

            return redirect()->route('exams.index')
                ->with('success', 'Examen mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'examen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam): \Illuminate\Http\RedirectResponse
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Exam not found');
        }

        try {
            // Check if exam is in progress or completed
            if (in_array($exam->status, ['in_progress', 'passed', 'failed'])) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer un examen en cours ou terminé');
            }

            $exam->delete();

            return redirect()->route('exams.index')
                ->with('success', 'Examen supprimé avec succès');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'examen: ' . $e->getMessage());
        }
    }

    /**
     * Start an exam.
     */
    public function start(Exam $exam): JsonResponse
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exam->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled exams can be started'
            ], Response::HTTP_CONFLICT);
        }

        $exam->update([
            'status' => 'in_progress',
            'scheduled_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $exam,
            'message' => 'Exam started successfully'
        ]);
    }

    /**
     * Complete an exam with results.
     */
    public function complete(Request $request, Exam $exam): JsonResponse
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exam->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Only exams in progress can be completed'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:1|max:100',
            'exam_results' => 'nullable|array',
            'examiner_notes' => 'nullable|string|max:1000',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $score = $request->score;
        $maxScore = $request->max_score;
        $passingScore = 70; // Default passing score, can be made configurable

        $status = $score >= $passingScore ? 'passed' : 'failed';

        $exam->update([
            'status' => $status,
            'completed_at' => now(),
            'score' => $score,
            'max_score' => $maxScore,
            'exam_results' => $request->exam_results,
            'examiner_notes' => $request->examiner_notes,
            'feedback' => $request->feedback,
            'retake_date' => $status === 'failed' ? now()->addDays(7) : null
        ]);

        return response()->json([
            'success' => true,
            'data' => $exam,
            'message' => 'Exam completed successfully'
        ]);
    }

    /**
     * Cancel an exam.
     */
    public function cancel(Request $request, Exam $exam): JsonResponse
    {
        // Check if exam belongs to current tenant
        if ($exam->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!in_array($exam->status, ['scheduled', 'in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled or in-progress exams can be cancelled'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $exam->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return response()->json([
            'success' => true,
            'data' => $exam,
            'message' => 'Exam cancelled successfully'
        ]);
    }

    /**
     * Get exams for a specific date.
     */
    public function byDate(Request $request): JsonResponse
    {
        $date = $request->get('date', now()->toDateString());
        
        $exams = Exam::with(['student', 'instructor'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $exams,
            'message' => 'Exams for date retrieved successfully'
        ]);
    }

    /**
     * Get exam statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Exam::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $stats = [
            'total_exams' => $query->count(),
            'scheduled' => $query->where('status', 'scheduled')->count(),
            'in_progress' => $query->where('status', 'in_progress')->count(),
            'passed' => $query->where('status', 'passed')->count(),
            'failed' => $query->where('status', 'failed')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
            'no_show' => $query->where('status', 'no_show')->count(),
            'pass_rate' => $query->whereIn('status', ['passed', 'failed'])->count() > 0 
                ? round(($query->where('status', 'passed')->count() / $query->whereIn('status', ['passed', 'failed'])->count()) * 100, 2)
                : 0,
            'average_score' => $query->whereNotNull('score')->avg('score'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Exam statistics retrieved successfully'
        ]);
    }

    /**
     * Get exam results by license category.
     */
    public function resultsByCategory(Request $request): JsonResponse
    {
        $query = Exam::where('tenant_id', auth()->user()->tenant_id)
            ->whereIn('status', ['passed', 'failed']);

        if ($request->has('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $results = $query->selectRaw('
                license_category,
                COUNT(*) as total_exams,
                SUM(CASE WHEN status = "passed" THEN 1 ELSE 0 END) as passed_exams,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_exams,
                ROUND(AVG(score), 2) as average_score,
                ROUND((SUM(CASE WHEN status = "passed" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as pass_rate
            ')
            ->groupBy('license_category')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => 'Exam results by category retrieved successfully'
        ]);
    }
}
