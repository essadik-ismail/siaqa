<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function dashboard()
    {
        $tenant = app('tenant');
        $subscription = $tenant->subscription;
        $invoices = $tenant->invoices()->latest()->take(10)->get();
        $usage = $tenant->usage()->get();

        return view('billing.dashboard', compact('tenant', 'subscription', 'invoices', 'usage'));
    }

    public function plans()
    {
        $plans = [
            'starter' => [
                'name' => 'Starter',
                'price' => 29.99,
                'features' => [
                    'vehicles' => 10,
                    'users' => 5,
                    'api_calls' => 1000,
                    'support' => 'email',
                ],
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 79.99,
                'features' => [
                    'vehicles' => 50,
                    'users' => 15,
                    'api_calls' => 5000,
                    'support' => 'priority',
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 199.99,
                'features' => [
                    'vehicles' => -1, // Unlimited
                    'users' => -1, // Unlimited
                    'api_calls' => -1, // Unlimited
                    'support' => 'dedicated',
                ],
            ],
        ];

        return view('billing.plans', compact('plans'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:starter,professional,enterprise',
            'payment_method_id' => 'required|string',
        ]);

        $tenant = app('tenant');

        try {
            // Create Stripe customer if not exists
            if (!$tenant->stripe_customer_id) {
                $customer = $this->subscriptionService->createCustomer($tenant, $request->payment_method_id);
                $tenant->update(['stripe_customer_id' => $customer->id]);
            }

            // Create subscription
            $planId = 'price_' . $request->plan;
            $subscription = $this->subscriptionService->createSubscription($tenant, $planId);

            return redirect()->route('billing.dashboard')
                ->with('success', 'Subscription created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        $tenant = app('tenant');
        
        try {
            $this->subscriptionService->updatePaymentMethod($tenant, $request->payment_method_id);

            return redirect()->back()->with('success', 'Payment method updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment method: ' . $e->getMessage());
        }
    }

    public function cancelSubscription()
    {
        $tenant = app('tenant');
        
        try {
            $this->subscriptionService->cancelSubscription($tenant);

            return redirect()->back()->with('success', 'Subscription cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    public function invoices()
    {
        $tenant = app('tenant');
        $invoices = $tenant->invoices()
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('billing.invoices', compact('invoices'));
    }

    public function downloadInvoice(Invoice $invoice)
    {
        $tenant = app('tenant');
        
        // Ensure the invoice belongs to the current tenant
        if ($invoice->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Generate PDF invoice
        $pdf = $this->generateInvoicePdf($invoice);
        
        return $pdf->download("invoice-{$invoice->id}.pdf");
    }

    protected function generateInvoicePdf(Invoice $invoice)
    {
        // Implementation for PDF generation
        // You can use packages like DomPDF or Snappy
        return response()->make('PDF content here');
    }

    public function usage()
    {
        $tenant = app('tenant');
        $usage = $tenant->usage()
            ->where('period', request('period', now()->format('Y-m')))
            ->get();

        return view('billing.usage', compact('usage'));
    }
} 