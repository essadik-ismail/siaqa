<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['createdBy', 'tenant'])
            ->where('tenant_id', auth()->user()->tenant_id);

        // Apply filters
        if ($request->has('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
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
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $reports = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reports,
            'message' => 'Reports retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get validated data and add tenant_id from authenticated user
            $data = $request->validated();
            $data['tenant_id'] = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;

            $report = Report::create($data);

            // Load relationships
            $report->load(['createdBy', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Report created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create report',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): JsonResponse
    {
        // Check if report belongs to current tenant
        if ($report->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $report->load(['createdBy', 'tenant']);

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Report retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report): JsonResponse
    {
        // Check if report belongs to current tenant
        if ($report->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $report->update($request->validated());
            $report->load(['createdBy', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Report updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report): JsonResponse
    {
        // Check if report belongs to current tenant
        if ($report->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete report',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Generate a report.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'report_type' => 'required|in:revenue,student_progress,instructor_performance,vehicle_usage,exam_results,custom',
            'filters' => 'nullable|array',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $report = Report::create([
                'tenant_id' => auth()->user()->tenant_id,
                'created_by' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'report_type' => $request->report_type,
                'filters' => $request->filters,
                'status' => 'generating'
            ]);

            // Here you would implement the actual report generation logic
            // For now, we'll simulate it
            $this->generateReportData($report);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Report generation started successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Download a report file.
     */
    public function download(Report $report): JsonResponse
    {
        // Check if report belongs to current tenant
        if ($report->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$report->isCompleted() || !$report->hasFile()) {
            return response()->json([
                'success' => false,
                'message' => 'Report file not available'
            ], Response::HTTP_NOT_FOUND);
        }

        $filePath = storage_path('app/' . $report->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Report file not found on disk'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->download($filePath, $report->name . '.pdf');
    }

    /**
     * Get report statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Report::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_reports' => $query->count(),
            'generating' => $query->where('status', 'generating')->count(),
            'completed' => $query->where('status', 'completed')->count(),
            'failed' => $query->where('status', 'failed')->count(),
            'by_type' => $query->selectRaw('report_type, COUNT(*) as count')
                ->groupBy('report_type')
                ->get(),
            'by_status' => $query->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Report statistics retrieved successfully'
        ]);
    }

    /**
     * Get available report types.
     */
    public function types(): JsonResponse
    {
        $types = [
            'revenue' => 'Revenue Report',
            'student_progress' => 'Student Progress Report',
            'instructor_performance' => 'Instructor Performance Report',
            'vehicle_usage' => 'Vehicle Usage Report',
            'exam_results' => 'Exam Results Report',
            'custom' => 'Custom Report'
        ];

        return response()->json([
            'success' => true,
            'data' => $types,
            'message' => 'Report types retrieved successfully'
        ]);
    }

    /**
     * Generate report data (placeholder method).
     */
    private function generateReportData(Report $report): void
    {
        // This is a placeholder method
        // In a real implementation, you would:
        // 1. Query the database based on report type and filters
        // 2. Process the data
        // 3. Generate the report file (PDF, Excel, etc.)
        // 4. Save the file and update the report status

        // For now, we'll just mark it as completed with dummy data
        $report->update([
            'status' => 'completed',
            'data' => ['message' => 'Report data generated successfully'],
            'file_path' => 'reports/' . $report->id . '.pdf',
            'generated_at' => now()
        ]);
    }
}
