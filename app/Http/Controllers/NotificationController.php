<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\StoreNotificationRequest;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Notification::with(['user', 'student', 'instructor', 'tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
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
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'message' => 'Notifications retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $notification = Notification::create($request->validated());

            // Load relationships
            $notification->load(['user', 'student', 'instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $notification,
                'message' => 'Notification created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $notification->load(['user', 'student', 'instructor', 'tenant']);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $notification->update($request->validated());
            $notification->load(['user', 'student', 'instructor', 'tenant']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $notification,
                'message' => 'Notification updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $notification->update(['is_read' => false]);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification marked as unread'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Notification::where('tenant_id', $tenantId)
            ->where('is_read', false);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $updated = $query->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => ['updated_count' => $updated],
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Notification::where('tenant_id', $tenantId)
            ->where('is_read', false);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $count = $query->count();

        return response()->json([
            'success' => true,
            'data' => ['unread_count' => $count],
            'message' => 'Unread notifications count retrieved'
        ]);
    }

    /**
     * Send notification.
     */
    public function send(Notification $notification): JsonResponse
    {
        // Check if notification belongs to current tenant
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        if ($notification->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($notification->sent_at) {
            return response()->json([
                'success' => false,
                'message' => 'Notification already sent'
            ], Response::HTTP_CONFLICT);
        }

        // Here you would implement the actual sending logic
        // For now, we'll just mark it as sent
        $notification->update(['sent_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification sent successfully'
        ]);
    }

    /**
     * Get notification statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $tenantId = auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
        $query = Notification::where('tenant_id', $tenantId);

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_notifications' => $query->count(),
            'unread' => $query->where('is_read', false)->count(),
            'read' => $query->where('is_read', true)->count(),
            'sent' => $query->whereNotNull('sent_at')->count(),
            'scheduled' => $query->whereNotNull('scheduled_at')->whereNull('sent_at')->count(),
            'by_type' => $query->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'by_channel' => $query->selectRaw('channel, COUNT(*) as count')
                ->groupBy('channel')
                ->get(),
            'by_priority' => $query->selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Notification statistics retrieved successfully'
        ]);
    }
}