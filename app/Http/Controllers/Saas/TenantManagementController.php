<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\TenantCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantManagementController extends Controller
{
    protected $tenantCreationService;

    public function __construct(TenantCreationService $tenantCreationService)
    {
        $this->tenantCreationService = $tenantCreationService;
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
            'company_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'plan_name' => 'required|in:starter,professional,enterprise',
            'trial_ends_at' => 'nullable|date',
            'max_users' => 'nullable|integer|min:1',
            'max_vehicles' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the tenant
            $tenant = Tenant::create([
                'name' => $request->company_name, // Use 'name' field from database
                'company_name' => $request->company_name,
                'domain' => $request->domain,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'address' => $request->address,
                'notes' => $request->notes,
                'trial_ends_at' => $request->trial_ends_at,
                'max_users' => $request->max_users,
                'max_vehicles' => $request->max_vehicles,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Create the agency for this tenant
            $tenant->agence()->create([
                'nom_agence' => $request->company_name,
                'adresse' => $request->address,
                'is_active' => true,
            ]);

            // Create subscription if plan is selected
            if ($request->plan_name) {
                $tenant->subscription()->create([
                    'plan_name' => $request->plan_name,
                    'starts_at' => now(),
                    'status' => 'active',
                    'features' => $this->getPlanFeatures($request->plan_name),
                ]);
            }

            // Send welcome email if requested
            if ($request->boolean('send_welcome_email')) {
                // TODO: Implement welcome email sending
            }

            return redirect()->route('saas.tenants.index')
                ->with('success', 'Tenant and agency created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create tenant: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['subscription', 'usage', 'agence', 'users']);
        
        return view('saas.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('saas.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain,' . $tenant->id,
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'plan_name' => 'required|in:starter,professional,enterprise',
            'trial_ends_at' => 'nullable|date',
            'max_users' => 'nullable|integer|min:1',
            'max_vehicles' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update tenant
        $tenant->update([
            'company_name' => $request->company_name,
            'domain' => $request->domain,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'trial_ends_at' => $request->trial_ends_at,
            'max_users' => $request->max_users,
            'max_vehicles' => $request->max_vehicles,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Update or create agency
        if ($tenant->agence) {
            $tenant->agence->update([
                'nom_agence' => $request->company_name,
                'adresse' => $request->address,
            ]);
        } else {
            $tenant->agence()->create([
                'nom_agence' => $request->company_name,
                'adresse' => $request->address,
                'is_active' => true,
            ]);
        }

        // Update subscription if plan changed
        if ($request->plan_name && (!$tenant->subscription || $tenant->subscription->plan_name !== $request->plan_name)) {
            if ($tenant->subscription) {
                $tenant->subscription->update([
                    'plan_name' => $request->plan_name,
                    'features' => $this->getPlanFeatures($request->plan_name),
                ]);
            } else {
                $tenant->subscription()->create([
                    'plan_name' => $request->plan_name,
                    'starts_at' => now(),
                    'status' => 'active',
                    'features' => $this->getPlanFeatures($request->plan_name),
                ]);
            }
        }

        return redirect()->route('saas.tenants.index')
            ->with('success', 'Tenant and agency updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        try {
            $this->tenantCreationService->deleteTenant($tenant);
            return redirect()->route('saas.tenants.index')
                ->with('success', 'Tenant deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete tenant: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Tenant $tenant)
    {
        if ($tenant->is_active) {
            $this->tenantCreationService->suspendTenant($tenant);
            $message = 'Tenant suspended successfully.';
        } else {
            $this->tenantCreationService->activateTenant($tenant);
            $message = 'Tenant activated successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function billing(Tenant $tenant)
    {
        $tenant->load(['subscription', 'invoices']);
        
        return view('saas.tenants.billing', compact('tenant'));
    }

    public function updateBilling(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|in:starter,professional,enterprise',
            'trial_ends_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update tenant trial end date
        $tenant->update([
            'trial_ends_at' => $request->trial_ends_at,
        ]);

        // Update subscription plan if changed
        if ($request->plan_name && (!$tenant->subscription || $tenant->subscription->plan_name !== $request->plan_name)) {
            if ($tenant->subscription) {
                $tenant->subscription->update([
                    'plan_name' => $request->plan_name,
                    'features' => $this->getPlanFeatures($request->plan_name),
                ]);
            } else {
                $tenant->subscription()->create([
                    'plan_name' => $request->plan_name,
                    'starts_at' => now(),
                    'status' => 'active',
                    'features' => $this->getPlanFeatures($request->plan_name),
                ]);
            }
        }

        return redirect()->route('saas.tenants.billing', $tenant)
            ->with('success', 'Billing information updated successfully.');
    }

    /**
     * Get plan features based on plan name
     */
    protected function getPlanFeatures(string $planName): array
    {
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
                'vehicles' => -1, // Unlimited
                'users' => -1, // Unlimited
                'api_calls' => -1, // Unlimited
                'support' => 'dedicated',
            ],
        ];

        return $features[$planName] ?? $features['starter'];
    }
}
