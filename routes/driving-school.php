<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TheoryClassController;
use App\Http\Controllers\StudentProgressController;
use App\Http\Controllers\InstructorAvailabilityController;
use App\Http\Controllers\VehicleAssignmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Driving School API Routes
|--------------------------------------------------------------------------
|
| These routes are specifically for the driving school SaaS platform.
| All routes are protected by authentication and tenant middleware.
|
*/

// API Routes with authentication and tenant middleware
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    
    // Instructor Routes
    Route::apiResource('instructors', InstructorController::class);
    Route::get('instructors/{instructor}/schedule', [InstructorController::class, 'schedule']);
    Route::get('instructors/{instructor}/performance', [InstructorController::class, 'performance']);
    Route::patch('instructors/{instructor}/toggle-availability', [InstructorController::class, 'toggleAvailability']);

    // Student Routes
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/progress', [StudentController::class, 'progress']);
    Route::get('students/{student}/schedule', [StudentController::class, 'schedule']);
    Route::patch('students/{student}/status', [StudentController::class, 'updateStatus']);
    Route::get('students/{student}/payments', [StudentController::class, 'payments']);

    // Lesson Routes
    Route::apiResource('lessons', LessonController::class);
    Route::patch('lessons/{lesson}/start', [LessonController::class, 'start']);
    Route::patch('lessons/{lesson}/complete', [LessonController::class, 'complete']);
    Route::patch('lessons/{lesson}/cancel', [LessonController::class, 'cancel']);
    Route::get('lessons/by-date', [LessonController::class, 'byDate']);
    Route::get('lessons/available-slots', [LessonController::class, 'availableSlots']);

    // Exam Routes
    Route::apiResource('exams', ExamController::class);
    Route::patch('exams/{exam}/start', [ExamController::class, 'start']);
    Route::patch('exams/{exam}/complete', [ExamController::class, 'complete']);
    Route::patch('exams/{exam}/cancel', [ExamController::class, 'cancel']);
    Route::get('exams/by-date', [ExamController::class, 'byDate']);
    Route::get('exams/statistics', [ExamController::class, 'statistics']);
    Route::get('exams/results-by-category', [ExamController::class, 'resultsByCategory']);

    // Payment Routes
    Route::apiResource('payments', PaymentController::class);
    Route::patch('payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid']);
    Route::get('payments/statistics', [PaymentController::class, 'statistics']);
    Route::get('payments/overdue', [PaymentController::class, 'overdue']);
    Route::get('payments/by-student/{student}', [PaymentController::class, 'byStudent']);
    Route::get('payments/summary-by-type', [PaymentController::class, 'summaryByType']);

    // Package Routes
    Route::apiResource('packages', PackageController::class);
    Route::patch('packages/{package}/toggle-active', [PackageController::class, 'toggleActive']);
    Route::get('packages/by-category', [PackageController::class, 'byCategory']);
    Route::get('packages/statistics', [PackageController::class, 'statistics']);
    Route::get('packages/popular', [PackageController::class, 'popular']);

    // Theory Class Routes
    Route::apiResource('theory-classes', TheoryClassController::class);
    Route::patch('theory-classes/{theoryClass}/start', [TheoryClassController::class, 'start']);
    Route::patch('theory-classes/{theoryClass}/complete', [TheoryClassController::class, 'complete']);
    Route::patch('theory-classes/{theoryClass}/cancel', [TheoryClassController::class, 'cancel']);
    Route::post('theory-classes/{theoryClass}/enroll', [TheoryClassController::class, 'enroll']);
    Route::delete('theory-classes/{theoryClass}/unenroll', [TheoryClassController::class, 'unenroll']);
    Route::get('theory-classes/by-date', [TheoryClassController::class, 'byDate']);
    Route::get('theory-classes/available', [TheoryClassController::class, 'available']);

    // Student Progress Routes
    Route::apiResource('student-progress', StudentProgressController::class);
    Route::get('student-progress/by-student/{student}', [StudentProgressController::class, 'byStudent']);
    Route::get('student-progress/by-skill-category', [StudentProgressController::class, 'bySkillCategory']);
    Route::get('student-progress/statistics', [StudentProgressController::class, 'statistics']);
    Route::get('student-progress/skill-categories', [StudentProgressController::class, 'skillCategories']);
    Route::get('student-progress/skills-by-category', [StudentProgressController::class, 'skillsByCategory']);
    Route::patch('student-progress/{studentProgress}/mark-completed', [StudentProgressController::class, 'markCompleted']);
    Route::patch('student-progress/{studentProgress}/update-skill-level', [StudentProgressController::class, 'updateSkillLevel']);

    // Instructor Availability Routes
    Route::apiResource('instructor-availability', InstructorAvailabilityController::class);
    Route::get('instructor-availability/by-instructor/{instructor}', [InstructorAvailabilityController::class, 'byInstructor']);
    Route::get('instructor-availability/by-day', [InstructorAvailabilityController::class, 'byDay']);
    Route::get('instructor-availability/available-instructors', [InstructorAvailabilityController::class, 'availableInstructors']);
    Route::patch('instructor-availability/{instructorAvailability}/toggle', [InstructorAvailabilityController::class, 'toggleAvailability']);
    Route::get('instructor-availability/statistics', [InstructorAvailabilityController::class, 'statistics']);
    Route::get('instructor-availability/{instructor}/weekly-schedule', [InstructorAvailabilityController::class, 'weeklySchedule']);

    // Vehicle Assignment Routes
    Route::apiResource('vehicle-assignments', VehicleAssignmentController::class);
    Route::post('vehicle-assignments/assign', [VehicleAssignmentController::class, 'assign']);
    Route::patch('vehicle-assignments/{vehicleAssignment}/return', [VehicleAssignmentController::class, 'return']);
    Route::get('vehicle-assignments/by-vehicle/{vehicle}', [VehicleAssignmentController::class, 'byVehicle']);
    Route::get('vehicle-assignments/by-instructor/{instructor}', [VehicleAssignmentController::class, 'byInstructor']);
    Route::get('vehicle-assignments/current', [VehicleAssignmentController::class, 'current']);
    Route::get('vehicle-assignments/statistics', [VehicleAssignmentController::class, 'statistics']);

    // Notification Routes
    Route::apiResource('notifications', NotificationController::class);
    Route::patch('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::patch('notifications/{notification}/mark-unread', [NotificationController::class, 'markAsUnread']);
    Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('notifications/{notification}/send', [NotificationController::class, 'send']);
    Route::get('notifications/statistics', [NotificationController::class, 'statistics']);

    // Report Routes
    Route::apiResource('reports', ReportController::class);
    Route::post('reports/generate', [ReportController::class, 'generate']);
    Route::get('reports/{report}/download', [ReportController::class, 'download']);
    Route::get('reports/statistics', [ReportController::class, 'statistics']);
    Route::get('reports/types', [ReportController::class, 'types']);

    // Analytics Routes
    Route::apiResource('analytics', AnalyticsController::class);
    Route::get('analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    Route::get('analytics/by-metric', [AnalyticsController::class, 'byMetric']);
    Route::get('analytics/summary', [AnalyticsController::class, 'summary']);
    Route::get('analytics/metrics', [AnalyticsController::class, 'metrics']);
    Route::post('analytics/generate', [AnalyticsController::class, 'generate']);

});

// Public routes (no authentication required)
Route::get('health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});
