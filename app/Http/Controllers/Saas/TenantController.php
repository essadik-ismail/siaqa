<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\TenantCreationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    protected $tenantCreationService;
    protected $subscriptionService;

    public function __construct(
        TenantCreationService $tenantCreationService,
        SubscriptionService $subscriptionService
    ) {
        $this->tenantCreationService = $tenantCreationService;
        $this->subscriptionService = $subscriptionService;
    }

    public function index()
    {
        $tenants = Tenant::with(['subscription', 'usage'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('saas.tenants.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'subscription_plan' => 'required|in:starter,professional,enterprise',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $tenant = $this->tenantCreationService->createTenant([
                'name' => $request->name,
                'domain' => $request->domain,
                'subscription_plan' => $request->subscription_plan,
                'admin_email' => $request->admin_email,
                'admin_password' => $request->admin_password,
            ]);

            return redirect()->route('saas.tenants.index')
                ->with('success', "Tenant '{$tenant->name}' created successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create tenant: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['subscription', 'usage', 'invoices']);
        
        $usageStats = $tenant->usage()
            ->where('period', now()->format('Y-m'))
            ->get()
            ->keyBy('feature');

        return view('saas.tenants.show', compact('tenant', 'usageStats'));
    }

    public function edit(Tenant $tenant)
    {
        return view('saas.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'subscription_plan' => 'required|in:starter,professional,enterprise',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tenant->update($request->only(['name', 'is_active', 'subscription_plan']));

        return redirect()->route('saas.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully!');
    }

    public function destroy(Tenant $tenant)
    {
        try {
            $this->tenantCreationService->deleteTenant($tenant);

            return redirect()->route('saas.tenants.index')
                ->with('success', "Tenant '{$tenant->name}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }

    public function suspend(Tenant $tenant)
    {
        $this->tenantCreationService->suspendTenant($tenant);

        return redirect()->back()
            ->with('success', "Tenant '{$tenant->name}' suspended successfully!");
    }

    public function activate(Tenant $tenant)
    {
        $this->tenantCreationService->activateTenant($tenant);

        return redirect()->back()
            ->with('success', "Tenant '{$tenant->name}' activated successfully!");
    }

    public function usage(Tenant $tenant)
    {
        $usage = $tenant->usage()
            ->where('period', request('period', now()->format('Y-m')))
            ->get();

        return view('saas.tenants.usage', compact('tenant', 'usage'));
    }

    public function billing(Tenant $tenant)
    {
        $invoices = $tenant->invoices()
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.tenants.billing', compact('tenant', 'invoices'));
    }
} 