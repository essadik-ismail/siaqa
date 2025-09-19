<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\Vehicule;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        
        // Base query for tenant-specific data
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        // Calculate real statistics
        $stats = [
            'total_students' => Student::where($tenantQuery)->count(),
            'total_instructors' => Instructor::where($tenantQuery)->count(),
            'total_lessons' => Lesson::where($tenantQuery)->count(),
            'total_exams' => Exam::where($tenantQuery)->count(),
            'total_vehicles' => Vehicule::where($tenantQuery)->count(),
            'total_revenue' => Payment::where($tenantQuery)->sum('amount') ?? 0,
            'estimated_revenue' => $this->calculateEstimatedRevenue($tenantId),
            'actual_revenue' => Payment::where($tenantQuery)->where('status', 'paid')->sum('amount') ?? 0,
            'current_month_revenue' => Payment::where($tenantQuery)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0,
            'current_students_revenue' => Payment::where($tenantQuery)
                ->whereDate('created_at', today())
                ->sum('amount') ?? 0,
        ];

        // Calculate utilization percentages
        $totalVehicles = $stats['total_vehicles'];
        $activeLessons = Lesson::where($tenantQuery)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->count();
        
        $stats['estimated_utilization'] = $totalVehicles > 0 ? min(100, round(($stats['estimated_revenue'] / max($stats['actual_revenue'], 1)) * 100)) : 0;
        $stats['actual_utilization'] = $totalVehicles > 0 ? min(100, round(($activeLessons / $totalVehicles) * 100)) : 0;

        // Add admin statistics if user is super admin
        if ($user->isSuperAdmin()) {
            $stats = array_merge($stats, [
                'total_users' => User::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'active_users' => User::where('is_active', true)->count(),
                'total_tenants' => Tenant::count(),
            ]);
        }

        // Get chart data
        $chartData = $this->getChartData($tenantId);
        
        // Get revenue breakdown data
        $revenueBreakdown = $this->getRevenueBreakdown($tenantId);
        
        // Get recent data for tabs (using get for frontend pagination)
        $recentVehicles = Vehicule::where($tenantQuery)
            ->with('marque')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $recentLessons = Lesson::where($tenantQuery)
            ->with(['student', 'instructor', 'vehicule.marque'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $recentExams = Exam::where($tenantQuery)
            ->with(['student', 'instructor'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard', compact('stats', 'chartData', 'revenueBreakdown', 'recentVehicles', 'recentLessons', 'recentExams'));
    }

    /**
     * Get data for a specific tab via AJAX
     */
    public function getTabData(Request $request)
    {
        $tab = $request->get('tab', 'vehicles');
        $user = auth()->user();
        $tenantId = $user->tenant_id;
        
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        switch ($tab) {
            case 'lessons':
                $data = Lesson::where($tenantQuery)
                    ->with(['student', 'instructor', 'vehicule.marque'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                    
            case 'exams':
                $data = Exam::where($tenantQuery)
                    ->with(['student', 'instructor'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                    
            case 'vehicles':
            default:
                $data = Vehicule::where($tenantQuery)
                    ->with('marque')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'html' => view('dashboard.partials.' . $tab, compact('data'))->render()
        ]);
    }

    /**
     * Calculate estimated revenue based on vehicle rental prices
     */
    private function calculateEstimatedRevenue($tenantId)
    {
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        $totalStudents = Student::where($tenantQuery)->count();
        $avgLessonRate = Lesson::where($tenantQuery)->avg('price') ?? 0;
        
        // Estimate based on 4 lessons per student per month at average rate
        return $totalStudents * $avgLessonRate * 4;
    }

    /**
     * Get chart data for revenue and reservations
     */
    private function getChartData($tenantId)
    {
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        // Get last 6 months of data
        $months = [];
        $revenueData = [];
        $lessonData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $revenue = Payment::where($tenantQuery)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'paid')
                ->sum('amount') ?? 0;
            $revenueData[] = $revenue;
            
            $lessons = Lesson::where($tenantQuery)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $lessonData[] = $lessons;
        }

        return [
            'months' => $months,
            'revenue' => $revenueData,
            'lessons' => $lessonData
        ];
    }

    /**
     * Calculate real revenue breakdown by category
     */
    private function getRevenueBreakdown($tenantId)
    {
        $tenantQuery = function($query) use ($tenantId) {
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        };

        // Get total revenue from paid payments
        $totalRevenue = Payment::where($tenantQuery)
            ->where('status', 'paid')
            ->sum('amount') ?? 0;

        if ($totalRevenue == 0) {
            return [
                'lesson_fees' => 0,
                'exam_fees' => 0,
                'package_fees' => 0,
                'other_services' => 0,
                'percentages' => [
                    'lesson_fees' => 0,
                    'exam_fees' => 0,
                    'package_fees' => 0,
                    'other_services' => 0
                ]
            ];
        }

        // For now, we'll estimate based on typical driving school business breakdown
        // In a real system, you'd have separate tables for different revenue types
        $lessonFees = $totalRevenue * 0.6; // 60% from lesson fees
        $examFees = $totalRevenue * 0.25; // 25% from exam fees
        $packageFees = $totalRevenue * 0.10; // 10% from package fees
        $otherServices = $totalRevenue * 0.05; // 5% from other services

        return [
            'lesson_fees' => $lessonFees,
            'exam_fees' => $examFees,
            'package_fees' => $packageFees,
            'other_services' => $otherServices,
            'percentages' => [
                'lesson_fees' => 60,
                'exam_fees' => 25,
                'package_fees' => 10,
                'other_services' => 5
            ]
        ];
    }
}

