@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Notification Details</h1>
        <div class="flex items-center space-x-2">
            <a href="{{ route('notifications.edit', $notification) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('notifications.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @if($notification->type == 'info')
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                            </div>
                        @elseif($notification->type == 'warning')
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                        @elseif($notification->type == 'error')
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $notification->title }}</h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-calendar mr-1"></i>{{ $notification->created_at->format('M d, Y H:i') }}</span>
                            @if($notification->user)
                                <span><i class="fas fa-user mr-1"></i>{{ $notification->user->name }}</span>
                            @endif
                            <span class="px-2 py-1 rounded-full text-xs {{ $notification->is_read ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $notification->is_read ? 'Read' : 'Unread' }}
                            </span>
                        </div>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $notification->message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Type</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $notification->type == 'info' ? 'bg-blue-100 text-blue-800' : ($notification->type == 'warning' ? 'bg-yellow-100 text-yellow-800' : ($notification->type == 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800')) }}">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Read Status</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $notification->is_read ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $notification->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm text-gray-900">{{ $notification->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Updated</span>
                        <span class="text-sm text-gray-900">{{ $notification->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-2">
                    @if(!$notification->is_read)
                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>Mark as Read
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('notifications.edit', $notification) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors block text-center">
                        <i class="fas fa-edit mr-2"></i>Edit Notification
                    </a>
                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
