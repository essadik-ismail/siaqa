<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Package::with(['tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('license_category')) {
            $query->where('license_category', $request->license_category);
        }

        if ($request->has('includes_exam')) {
            $query->where('includes_exam', $request->boolean('includes_exam'));
        }

        if ($request->has('includes_materials')) {
            $query->where('includes_materials', $request->boolean('includes_materials'));
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
        $packages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $packages,
            'message' => 'Packages retrieved successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $package = Package::create($request->validated());

            // Load relationships
            $package->load(['tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $package,
                'message' => 'Package created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package): JsonResponse
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $package->load(['tenant', 'studentPackages']);

        return response()->json([
            'success' => true,
            'data' => $package,
            'message' => 'Package retrieved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package): View
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            abort(404, 'Package not found');
        }

        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package): JsonResponse
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $package->update($request->validated());
            $package->load(['tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $package,
                'message' => 'Package updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package): JsonResponse
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Check if package has active student purchases
            $hasActivePurchases = $package->studentPackages()
                ->whereIn('status', ['active', 'expired'])
                ->exists();

            if ($hasActivePurchases) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete package with active student purchases'
                ], Response::HTTP_CONFLICT);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle package active status.
     */
    public function toggleActive(Package $package): JsonResponse
    {
        // Check if package belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($package->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $package->update(['is_active' => !$package->is_active]);

        return response()->json([
            'success' => true,
            'data' => $package,
            'message' => 'Package status updated successfully'
        ]);
    }

    /**
     * Get packages by license category.
     */
    public function byCategory(Request $request): JsonResponse
    {
        $request->validate([
            'license_category' => 'required|string|max:10'
        ]);

        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $packages = Package::with(['tenant'])
            ->where('tenant_id', $tenantId)
            ->where('license_category', $request->license_category)
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'message' => 'Packages by category retrieved successfully'
        ]);
    }

    /**
     * Get package statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Package::where('tenant_id', $tenantId);

        $stats = [
            'total_packages' => $query->count(),
            'active_packages' => $query->where('is_active', true)->count(),
            'inactive_packages' => $query->where('is_active', false)->count(),
            'average_price' => $query->avg('price'),
            'min_price' => $query->min('price'),
            'max_price' => $query->max('price'),
            'packages_with_exam' => $query->where('includes_exam', true)->count(),
            'packages_with_materials' => $query->where('includes_materials', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Package statistics retrieved successfully'
        ]);
    }

    /**
     * Get popular packages.
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);

        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $popularPackages = Package::with(['tenant'])
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->withCount('studentPackages')
            ->orderBy('student_packages_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularPackages,
            'message' => 'Popular packages retrieved successfully'
        ]);
    }
}
