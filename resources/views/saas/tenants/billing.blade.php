@extends('layouts.app')

@section('title', 'Tenant Billing - ' . $tenant->company_name)

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Billing Management</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.tenants.show', $tenant) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Tenant
            </a>
        </div>
    </div>

    <!-- Tenant Info Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $tenant->company_name }}</h2>
                <p class="text-gray-600">{{ $tenant->domain }}.{{ config('app.domain', 'localhost') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Subscription -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subscription Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-credit-card mr-3 text-blue-600"></i>
                    Current Subscription
                </h2>
                
                @if($tenant->subscription)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Plan</label>
                            <p class="text-gray-900 font-medium capitalize">{{ $tenant->subscription->plan_name ?? 'No Plan' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $tenant->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($tenant->subscription->status === 'trial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($tenant->subscription->status ?? 'inactive') }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Trial Ends</label>
                            <p class="text-gray-900">{{ $tenant->subscription->trial_ends_at ? $tenant->subscription->trial_ends_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created</label>
                            <p class="text-gray-900">{{ $tenant->subscription->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No subscription found</p>
                    </div>
                @endif
            </div>

            <!-- Plan Features -->
            @if($tenant->subscription && $tenant->subscription->plan_name)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-list-check mr-3 text-green-600"></i>
                        Plan Features
                    </h2>
                    
                    @php
                        $features = [
                            'starter' => [
                                'vehicles' => 10,
                                'users' => 5,
                                'api_calls' => 1000,
                                'support' => 'email',
                            ],
                            'professional' => [
                                'vehicles' => 50,
                                'users' => 15,
                                'api_calls' => 5000,
                                'support' => 'priority',
                            ],
                            'enterprise' => [
                                'vehicles' => -1,
                                'users' => -1,
                                'api_calls' => -1,
                                'support' => 'dedicated',
                            ],
                        ];
                        $currentFeatures = $features[$tenant->subscription->plan_name] ?? $features['starter'];
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-car text-blue-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Vehicles</p>
                                <p class="text-sm text-gray-500">{{ $currentFeatures['vehicles'] == -1 ? 'Unlimited' : $currentFeatures['vehicles'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Users</p>
                                <p class="text-sm text-gray-500">{{ $currentFeatures['users'] == -1 ? 'Unlimited' : $currentFeatures['users'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-code text-purple-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">API Calls</p>
                                <p class="text-sm text-gray-500">{{ $currentFeatures['api_calls'] == -1 ? 'Unlimited' : number_format($currentFeatures['api_calls']) }}/month</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-headset text-orange-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Support</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($currentFeatures['support']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Billing History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history mr-3 text-purple-600"></i>
                    Billing History
                </h2>
                
                @if($tenant->invoices && $tenant->invoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tenant->invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $invoice->invoice_number ?? $invoice->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $invoice->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($invoice->amount ?? 0) }} DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($invoice->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No billing history found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button onclick="openUpdateModal()" class="w-full btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>
                        Update Plan
                    </button>
                    <button class="w-full btn btn-secondary">
                        <i class="fas fa-download mr-2"></i>
                        Download Invoice
                    </button>
                    <button class="w-full btn btn-secondary">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Invoice
                    </button>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Vehicles</span>
                            <span class="font-medium">{{ $tenant->vehicles_count ?? 0 }}/{{ $currentFeatures['vehicles'] ?? 'N/A' }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $currentFeatures['vehicles'] > 0 ? min(100, (($tenant->vehicles_count ?? 0) / $currentFeatures['vehicles']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Users</span>
                            <span class="font-medium">{{ $tenant->users_count ?? 0 }}/{{ $currentFeatures['users'] ?? 'N/A' }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $currentFeatures['users'] > 0 ? min(100, (($tenant->users_count ?? 0) / $currentFeatures['users']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Plan Modal -->
<div id="updatePlanModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Subscription Plan</h3>
            <span class="close" onclick="closeUpdateModal()">&times;</span>
        </div>
        <form method="POST" action="{{ route('saas.tenants.billing.update', $tenant) }}">
            @csrf
            @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label for="plan_name" class="form-label">Plan</label>
                    <select id="plan_name" name="plan_name" class="form-control" required>
                        <option value="">Select Plan</option>
                        <option value="starter" {{ $tenant->subscription && $tenant->subscription->plan_name === 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="professional" {{ $tenant->subscription && $tenant->subscription->plan_name === 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise" {{ $tenant->subscription && $tenant->subscription->plan_name === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="trial_ends_at" class="form-label">Trial End Date</label>
                    <input type="date" id="trial_ends_at" name="trial_ends_at" class="form-control" 
                           value="{{ $tenant->subscription && $tenant->subscription->trial_ends_at ? $tenant->subscription->trial_ends_at->format('Y-m-d') : '' }}">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeUpdateModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Plan</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.form-actions {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
</style>

<script>
function openUpdateModal() {
    document.getElementById('updatePlanModal').style.display = 'block';
}

function closeUpdateModal() {
    document.getElementById('updatePlanModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('updatePlanModal');
    if (event.target == modal) {
        closeUpdateModal();
    }
}
</script>
@endsection


