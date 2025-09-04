<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Agence;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Role;
use App\Services\TenantCreationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            // Create the tenant directly instead of using the service
            $tenant = Tenant::create([
                'name' => $request->company_name,
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
            $agence = $tenant->agence()->create([
                'nom_agence' => $request->company_name,
                'adresse' => $request->address,
                'is_active' => true,
            ]);

            // Generate a hard password for the admin user
            $adminPassword = $this->generateHardPassword();
            
            // Create admin user for this tenant
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => $request->contact_email,
                'password' => Hash::make($adminPassword),
                'tenant_id' => $tenant->id,
                'agence_id' => $agence->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Assign admin role to the user
            $adminRole = Role::where('name', 'admin')->where('tenant_id', $tenant->id)->first();
            if (!$adminRole) {
                // Create admin role for this tenant if it doesn't exist
                $adminRole = Role::create([
                    'name' => 'admin',
                    'display_name' => 'Administrator',
                    'description' => 'Administrator role for ' . $tenant->company_name,
                    'tenant_id' => $tenant->id,
                ]);
            }
            
            $adminUser->assignRoles([$adminRole->id]);

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
                // TODO: Implement welcome email sending with admin credentials
                \Log::info("Admin user created for tenant {$tenant->company_name}", [
                    'email' => $request->contact_email,
                    'password' => $adminPassword,
                    'tenant_id' => $tenant->id
                ]);
            }

            return redirect()->route('saas.tenants.index')
                ->with('success', "Tenant '{$tenant->company_name}' and admin user created successfully!")
                ->with('admin_credentials', [
                    'email' => $request->contact_email,
                    'password' => $adminPassword,
                    'tenant_name' => $tenant->company_name
                ]);
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

    /**
     * Get plan features based on plan name
     */
    private function getPlanFeatures($planName)
    {
        $features = [
            'starter' => [
                'max_users' => 5,
                'max_vehicles' => 10,
                'storage_gb' => 5,
                'api_calls' => 1000,
                'support_level' => 'email'
            ],
            'professional' => [
                'max_users' => 15,
                'max_vehicles' => 50,
                'storage_gb' => 25,
                'api_calls' => 10000,
                'support_level' => 'priority'
            ],
            'enterprise' => [
                'max_users' => -1, // unlimited
                'max_vehicles' => -1, // unlimited
                'storage_gb' => 100,
                'api_calls' => -1, // unlimited
                'support_level' => 'dedicated'
            ]
        ];

        return $features[$planName] ?? $features['starter'];
    }

    /**
     * Generate a hard password for admin users
     */
    private function generateHardPassword(): string
    {
        // Generate a strong password with mixed case, numbers, and special characters
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $password = '';
        
        // Ensure at least one character from each category
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Fill the rest with random characters
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < 12; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize the order
        return str_shuffle($password);
    }
} 