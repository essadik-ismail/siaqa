<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['subscription', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.billing.index', compact('tenants'));
    }

    public function overview()
    {
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('amount'),
            'pending_amount' => Invoice::where('status', 'open')->sum('amount'),
            'overdue_amount' => Invoice::where('status', 'open')
                ->where('due_date', '<', now())
                ->sum('amount'),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'overdue_invoices' => Invoice::where('status', 'open')
                ->where('due_date', '<', now())
                ->count(),
        ];

        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topTenants = Tenant::withSum('invoices as total_paid', 'amount')
            ->whereHas('invoices', function ($query) {
                $query->where('status', 'paid');
            })
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();

        return view('saas.billing.overview', compact('stats', 'monthlyRevenue', 'topTenants'));
    }

    public function invoices()
    {
        $invoices = Invoice::with(['tenant', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('saas.billing.invoices', compact('invoices'));
    }
}
