@extends('layouts.app')

@section('title', 'Billing Overview')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Billing Overview</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.billing.invoices') }}" class="btn btn-secondary">
                <i class="fas fa-file-invoice mr-2"></i>
                View Invoices
            </a>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-download mr-2"></i>
                Export Report
            </a>
        </div>
    </div>

    <!-- Revenue Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Monthly Recurring Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up"></i>
                    {{ $monthlyGrowth }}% from last month
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Subscriptions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeSubscriptions }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600">
                    <i class="fas fa-arrow-up"></i>
                    {{ $subscriptionGrowth }}% from last month
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Overdue Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overduePayments }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600">
                    ${{ number_format($overdueAmount, 2) }} total overdue
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Churn Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $churnRate }}%</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600">
                    {{ $churnedSubscriptions }} subscriptions this month
                </span>
            </div>
        </div>
    </div>

    <!-- Revenue Chart and Plan Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trends</h3>
            <div class="h-64 flex items-center justify-center">
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-area text-4xl mb-3"></i>
                    <p>Revenue chart will be displayed here</p>
                    <p class="text-sm">Integration with Chart.js or similar library</p>
                </div>
            </div>
        </div>

        <!-- Plan Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Subscription Plan Distribution</h3>
            <div class="space-y-4">
                @foreach($planDistribution as $plan => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full 
                            {{ $plan === 'enterprise' ? 'bg-purple-500' : 
                               ($plan === 'professional' ? 'bg-blue-500' : 'bg-green-500') }}"></div>
                        <span class="ml-2 text-sm font-medium text-gray-700 capitalize">{{ $plan }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-900 font-medium">{{ $count }}</span>
                        <span class="text-sm text-gray-500">({{ round(($count / $totalSubscriptions) * 100, 1) }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions and Upcoming Renewals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Transactions</h3>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $transaction->tenant->company_name }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->description }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">${{ number_format($transaction->amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-3"></i>
                    <p>No recent transactions</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Renewals -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Renewals</h3>
            <div class="space-y-3">
                @forelse($upcomingRenewals as $renewal)
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $renewal->tenant->company_name }}</p>
                            <p class="text-xs text-gray-500">{{ $renewal->plan_name }} plan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">${{ number_format($renewal->amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $renewal->renewal_date->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar text-4xl mb-3"></i>
                    <p>No upcoming renewals</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-invoice text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Generate Invoice</p>
                    <p class="text-xs text-gray-500">Create new invoice for tenant</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-credit-card text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Payment Methods</p>
                    <p class="text-xs text-gray-500">Manage payment methods</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-cog text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Billing Settings</p>
                    <p class="text-xs text-gray-500">Configure billing preferences</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// This would be replaced with actual chart library integration
document.addEventListener('DOMContentLoaded', function() {
    console.log('Billing overview loaded');
});
</script>
@endpush
