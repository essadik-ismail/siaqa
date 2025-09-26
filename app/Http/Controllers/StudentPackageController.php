<?php

namespace App\Http\Controllers;

use App\Models\StudentPackage;
use App\Models\Student;
use App\Models\Package;
use App\Http\Requests\StoreStudentPackageRequest;
use App\Http\Requests\UpdateStudentPackageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;

class StudentPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        
        $query = StudentPackage::with(['student', 'package'])
            ->whereHas('student', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            });

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $studentPackages = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $students = Student::where('tenant_id', $tenantId)->orderBy('name')->get(['id', 'name']);
        $packages = Package::where('tenant_id', $tenantId)->orderBy('name')->get(['id', 'name']);

        return view('student-packages.index', compact('studentPackages', 'students', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $packages = Package::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'license_category']);

        return view('student-packages.create', compact('students', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentPackageRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['tenant_id'] = auth()->user()->tenant_id ?? 1;

            $studentPackage = StudentPackage::create($data);

            return response()->json([
                'success' => true,
                'data' => $studentPackage->load(['student', 'package']),
                'message' => 'Student package created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPackage $studentPackage): JsonResponse
    {
        // Check if student package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($studentPackage->student->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Student package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $studentPackage->load(['student', 'package']);

        return response()->json([
            'success' => true,
            'data' => $studentPackage,
            'message' => 'Student package retrieved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPackage $studentPackage): View
    {
        // Check if student package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($studentPackage->student->tenant_id !== $tenantId) {
            abort(404, 'Student package not found');
        }

        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $packages = Package::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'license_category']);

        return view('student-packages.edit', compact('studentPackage', 'students', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentPackageRequest $request, StudentPackage $studentPackage): JsonResponse
    {
        // Check if student package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($studentPackage->student->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Student package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $studentPackage->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $studentPackage->load(['student', 'package']),
                'message' => 'Student package updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPackage $studentPackage): JsonResponse
    {
        // Check if student package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($studentPackage->student->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Student package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $studentPackage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student package deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update student package status
     */
    public function updateStatus(Request $request, StudentPackage $studentPackage): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled'
        ]);

        // Check if student package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($studentPackage->student->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Student package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $studentPackage->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'data' => $studentPackage->load(['student', 'package']),
                'message' => 'Student package status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student package status',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get student package statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;

            $stats = [
                'total' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->count(),
                'pending' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('status', 'pending')->count(),
                'active' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('status', 'active')->count(),
                'completed' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('status', 'completed')->count(),
                'cancelled' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('status', 'cancelled')->count(),
                'total_revenue' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->sum('price'),
                'average_price' => StudentPackage::whereHas('student', function($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->avg('price')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Student package statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student package statistics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get student packages by student
     */
    public function byStudent(Student $student): JsonResponse
    {
        // Check if student belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($student->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $studentPackages = StudentPackage::with(['package'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $studentPackages,
            'message' => 'Student packages retrieved successfully'
        ]);
    }

    /**
     * Get student packages by package
     */
    public function byPackage(Package $package): JsonResponse
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $studentPackages = StudentPackage::with(['student'])
            ->where('package_id', $package->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $studentPackages,
            'message' => 'Student packages retrieved successfully'
        ]);
    }
}
