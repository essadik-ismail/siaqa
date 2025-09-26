@extends('layouts.app')

@section('title', 'Student Payments - ' . $student->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('students.show', $student) }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Payment History</h1>
                    <p class="mt-2 text-gray-600">{{ $student->name }}'s payment records and transactions</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Payments List -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Paid</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($student->total_paid, 2) }} DH</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Due</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($student->total_due, 2) }} DH</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-12 h-12 {{ $student->total_due - $student->total_paid > 0 ? 'bg-yellow-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center">
                                <i class="fas fa-balance-scale {{ $student->total_due - $student->total_paid > 0 ? 'text-yellow-600' : 'text-green-600' }}"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Balance</p>
                                <p class="text-2xl font-bold {{ $student->total_due - $student->total_paid > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($student->total_due - $student->total_paid, 2) }} DH
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                    </div>
                    <div class="p-6">
                        @forelse($payments as $payment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3 last:mb-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 {{ $payment->status == 'paid' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                                        <i class="fas fa-credit-card {{ $payment->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $payment->description ?? 'Payment' }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $payment->created_at->format('M d, Y H:i') }}
                                            @if($payment->lesson)
                                                • Lesson: {{ $payment->lesson->title }}
                                            @endif
                                            @if($payment->exam)
                                                • Exam: {{ $payment->exam->title }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Payment Method: {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-semibold text-gray-900">{{ number_format($payment->amount, 2) }} DH</div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $payment->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                        ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-red-100 text-red-800') 
                                    }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    @if($payment->paid_at)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Paid: {{ $payment->paid_at->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No payment records found</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Column - Summary & Actions -->
            <div class="space-y-6">
                <!-- Payment Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Payments</span>
                            <span class="font-semibold text-gray-900">{{ $payments->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Paid Payments</span>
                            <span class="font-semibold text-green-600">{{ $payments->where('status', 'paid')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Pending Payments</span>
                            <span class="font-semibold text-yellow-600">{{ $payments->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Failed Payments</span>
                            <span class="font-semibold text-red-600">{{ $payments->where('status', 'failed')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h3>
                    <div class="space-y-3">
                        @php
                            $paymentMethods = $payments->groupBy('payment_method');
                        @endphp
                        @forelse($paymentMethods as $method => $methodPayments)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-credit-card text-gray-400"></i>
                                    <span class="font-medium text-gray-900">{{ ucfirst($method ?: 'Unknown') }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $methodPayments->count() }} payments</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No payment methods recorded</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-plus mr-2"></i>
                            Record Payment
                        </a>
                        <a href="{{ route('students.progress', $student) }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                            <i class="fas fa-chart-line mr-2"></i>
                            View Progress
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @forelse($payments->take(3) as $payment)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 {{ $payment->status == 'paid' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                                    <i class="fas fa-credit-card {{ $payment->status == 'paid' ? 'text-green-600' : 'text-yellow-600' }} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($payment->amount, 2) }} DH</p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                    $payment->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                    ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800') 
                                }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No recent payments</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
