<?php

namespace App\Http\Controllers;

use App\Http\Requests\Analytics\StoreAnalyticsRequest;
use App\Http\Requests\Analytics\UpdateAnalyticsRequest;
use App\Models\Analytics;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Analytics::with(['tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('metric_name')) {
            $query->where('metric_name', $request->metric_name);
        }

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('metric_name', 'like', "%{$search}%");
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $analytics = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $analytics,
            'message' => 'Analytics retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnalyticsRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $analytics = Analytics::create($request->validated());

            // Load relationships
            $analytics->load(['tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'message' => 'Analytics created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create analytics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Analytics $analytics): JsonResponse
    {
        // Check if analytics belongs to current tenant
        if ($analytics->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $analytics->load(['tenant']);

        return response()->json([
            'success' => true,
            'data' => $analytics,
            'message' => 'Analytics retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnalyticsRequest $request, Analytics $analytics): JsonResponse
    {
        // Check if analytics belongs to current tenant
        if ($analytics->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $analytics->update($request->validated());
            $analytics->load(['tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'message' => 'Analytics updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update analytics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Analytics $analytics): JsonResponse
    {
        // Check if analytics belongs to current tenant
        if ($analytics->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $analytics->delete();

            return response()->json([
                'success' => true,
                'message' => 'Analytics deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete analytics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get dashboard analytics.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $tenantId = auth()->user()->tenant_id;
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Get basic statistics
        $stats = [
            'total_students' => \App\Models\Student::where('tenant_id', $tenantId)->count(),
            'active_students' => \App\Models\Student::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'total_instructors' => \App\Models\Instructor::where('tenant_id', $tenantId)->count(),
            'active_instructors' => \App\Models\Instructor::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'total_lessons' => \App\Models\Lesson::where('tenant_id', $tenantId)->count(),
            'completed_lessons' => \App\Models\Lesson::where('tenant_id', $tenantId)->where('status', 'completed')->count(),
            'total_exams' => \App\Models\Exam::where('tenant_id', $tenantId)->count(),
            'passed_exams' => \App\Models\Exam::where('tenant_id', $tenantId)->where('status', 'passed')->count(),
            'total_revenue' => \App\Models\Payment::where('tenant_id', $tenantId)->where('status', 'paid')->sum('amount_paid'),
            'pending_payments' => \App\Models\Payment::where('tenant_id', $tenantId)->where('status', 'pending')->sum('amount'),
        ];

        // Get recent activities
        $recentLessons = \App\Models\Lesson::with(['student', 'instructor'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->orderBy('scheduled_at', 'desc')
            ->limit(5)
            ->get();

        $recentExams = \App\Models\Exam::with(['student', 'instructor'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->orderBy('scheduled_at', 'desc')
            ->limit(5)
            ->get();

        // Get chart data
        $chartData = $this->getChartData($tenantId, $dateFrom, $dateTo);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_lessons' => $recentLessons,
                'recent_exams' => $recentExams,
                'charts' => $chartData,
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ]
            ],
            'message' => 'Dashboard analytics retrieved successfully'
        ]);
    }

    /**
     * Get analytics by metric.
     */
    public function byMetric(Request $request): JsonResponse
    {
        $request->validate([
            'metric_name' => 'required|string|max:100'
        ]);

        $query = Analytics::where('tenant_id', auth()->user()->tenant_id)
            ->where('metric_name', $request->metric_name);

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $analytics = $query->orderBy('date')->get();

        return response()->json([
            'success' => true,
            'data' => $analytics,
            'message' => 'Analytics by metric retrieved successfully'
        ]);
    }

    /**
     * Get analytics summary.
     */
    public function summary(Request $request): JsonResponse
    {
        $query = Analytics::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $summary = $query->selectRaw('
                metric_name,
                COUNT(*) as count,
                AVG(metric_value) as average_value,
                MIN(metric_value) as min_value,
                MAX(metric_value) as max_value,
                SUM(metric_value) as total_value
            ')
            ->groupBy('metric_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $summary,
            'message' => 'Analytics summary retrieved successfully'
        ]);
    }

    /**
     * Get available metrics.
     */
    public function metrics(): JsonResponse
    {
        $metrics = [
            'total_students' => 'Total Students',
            'active_students' => 'Active Students',
            'new_students' => 'New Students',
            'graduated_students' => 'Graduated Students',
            'total_instructors' => 'Total Instructors',
            'active_instructors' => 'Active Instructors',
            'total_lessons' => 'Total Lessons',
            'completed_lessons' => 'Completed Lessons',
            'cancelled_lessons' => 'Cancelled Lessons',
            'total_exams' => 'Total Exams',
            'passed_exams' => 'Passed Exams',
            'failed_exams' => 'Failed Exams',
            'total_revenue' => 'Total Revenue',
            'monthly_revenue' => 'Monthly Revenue',
            'daily_revenue' => 'Daily Revenue',
            'outstanding_payments' => 'Outstanding Payments',
            'vehicle_utilization' => 'Vehicle Utilization',
            'instructor_utilization' => 'Instructor Utilization',
            'student_satisfaction' => 'Student Satisfaction',
            'exam_pass_rate' => 'Exam Pass Rate',
        ];

        return response()->json([
            'success' => true,
            'data' => $metrics,
            'message' => 'Available metrics retrieved successfully'
        ]);
    }

    /**
     * Generate analytics data.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'metrics' => 'required|array',
            'metrics.*' => 'string|max:100'
        ]);

        try {
            DB::beginTransaction();

            $tenantId = auth()->user()->tenant_id;
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            $metrics = $request->metrics;

            $generated = [];

            foreach ($metrics as $metric) {
                $value = $this->calculateMetricValue($metric, $tenantId, $dateFrom, $dateTo);
                
                $analytics = Analytics::create([
                    'tenant_id' => $tenantId,
                    'date' => now()->toDateString(),
                    'metric_name' => $metric,
                    'metric_value' => $value,
                    'dimensions' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'generated_at' => now()->toISOString()
                    ]
                ]);

                $generated[] = $analytics;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $generated,
                'message' => 'Analytics generated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate analytics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get chart data for dashboard.
     */
    private function getChartData($tenantId, $dateFrom, $dateTo): array
    {
        // Revenue chart data
        $revenueData = \App\Models\Payment::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->whereBetween('paid_date', [$dateFrom, $dateTo])
            ->selectRaw('DATE(paid_date) as date, SUM(amount_paid) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Lessons chart data
        $lessonsData = \App\Models\Lesson::where('tenant_id', $tenantId)
            ->whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as lessons')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Exams chart data
        $examsData = \App\Models\Exam::where('tenant_id', $tenantId)
            ->whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as exams')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'revenue' => $revenueData,
            'lessons' => $lessonsData,
            'exams' => $examsData
        ];
    }

    /**
     * Calculate metric value.
     */
    private function calculateMetricValue($metric, $tenantId, $dateFrom, $dateTo): float
    {
        switch ($metric) {
            case 'total_students':
                return \App\Models\Student::where('tenant_id', $tenantId)->count();
            case 'active_students':
                return \App\Models\Student::where('tenant_id', $tenantId)->where('status', 'active')->count();
            case 'new_students':
                return \App\Models\Student::where('tenant_id', $tenantId)
                    ->whereBetween('registration_date', [$dateFrom, $dateTo])->count();
            case 'graduated_students':
                return \App\Models\Student::where('tenant_id', $tenantId)->where('status', 'graduated')->count();
            case 'total_instructors':
                return \App\Models\Instructor::where('tenant_id', $tenantId)->count();
            case 'active_instructors':
                return \App\Models\Instructor::where('tenant_id', $tenantId)->where('status', 'active')->count();
            case 'total_lessons':
                return \App\Models\Lesson::where('tenant_id', $tenantId)->count();
            case 'completed_lessons':
                return \App\Models\Lesson::where('tenant_id', $tenantId)->where('status', 'completed')->count();
            case 'cancelled_lessons':
                return \App\Models\Lesson::where('tenant_id', $tenantId)->where('status', 'cancelled')->count();
            case 'total_exams':
                return \App\Models\Exam::where('tenant_id', $tenantId)->count();
            case 'passed_exams':
                return \App\Models\Exam::where('tenant_id', $tenantId)->where('status', 'passed')->count();
            case 'failed_exams':
                return \App\Models\Exam::where('tenant_id', $tenantId)->where('status', 'failed')->count();
            case 'total_revenue':
                return \App\Models\Payment::where('tenant_id', $tenantId)->where('status', 'paid')->sum('amount_paid');
            case 'monthly_revenue':
                return \App\Models\Payment::where('tenant_id', $tenantId)
                    ->where('status', 'paid')
                    ->whereBetween('paid_date', [$dateFrom, $dateTo])
                    ->sum('amount_paid');
            case 'daily_revenue':
                return \App\Models\Payment::where('tenant_id', $tenantId)
                    ->where('status', 'paid')
                    ->whereDate('paid_date', now()->toDateString())
                    ->sum('amount_paid');
            case 'outstanding_payments':
                return \App\Models\Payment::where('tenant_id', $tenantId)
                    ->where('status', 'pending')
                    ->sum('amount');
            case 'exam_pass_rate':
                $totalExams = \App\Models\Exam::where('tenant_id', $tenantId)
                    ->whereIn('status', ['passed', 'failed'])->count();
                $passedExams = \App\Models\Exam::where('tenant_id', $tenantId)
                    ->where('status', 'passed')->count();
                return $totalExams > 0 ? ($passedExams / $totalExams) * 100 : 0;
            default:
                return 0;
        }
    }
}