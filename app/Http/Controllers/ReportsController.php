<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use App\Models\Vehicule;
use App\Models\Contrat;
use App\Models\Client;
use App\Models\Charge;
use App\Models\Assurance;
use App\Models\Vidange;
use App\Models\Visite;
use App\Models\Intervention;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the main reports dashboard.
     */
    public function index(): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id

        // Financial Data
        $totalRevenue = $this->getTotalRevenue($tenantId);
        $revenueGrowth = $this->getRevenueGrowth($tenantId);
        $todayRevenue = $this->getTodayRevenue($tenantId);
        $weekRevenue = $this->getWeekRevenue($tenantId);
        $monthRevenue = $this->getMonthRevenue($tenantId);
        $revenueChartData = $this->getRevenueChartData($tenantId);

        // Operational Data
        $activeRentals = $this->getActiveRentals($tenantId);
        $availableVehicles = $this->getAvailableVehicles($tenantId);
        $fleetUtilization = $this->getFleetUtilization($tenantId);
        $fleetStatus = $this->getFleetStatus($tenantId);
        $popularCategories = $this->getPopularCategories($tenantId);
        $topPerformingVehicles = $this->getTopPerformingVehicles($tenantId);

        // Customer Data
        $customerSatisfaction = $this->getCustomerSatisfaction($tenantId);
        $totalCustomers = $this->getTotalCustomers($tenantId);
        $newCustomersThisMonth = $this->getNewCustomersThisMonth($tenantId);
        $repeatCustomers = $this->getRepeatCustomers($tenantId);
        $averageRentalDuration = $this->getAverageRentalDuration($tenantId);

        // Maintenance Data
        $maintenanceCosts = $this->getMaintenanceCosts($tenantId);

        // Seasonal Data
        $seasonalTrends = $this->getSeasonalTrends($tenantId);

        return view('reports.index', compact(
            'totalRevenue',
            'revenueGrowth',
            'todayRevenue',
            'weekRevenue',
            'monthRevenue',
            'revenueChartData',
            'activeRentals',
            'availableVehicles',
            'fleetUtilization',
            'fleetStatus',
            'popularCategories',
            'topPerformingVehicles',
            'customerSatisfaction',
            'totalCustomers',
            'newCustomersThisMonth',
            'repeatCustomers',
            'averageRentalDuration',
            'maintenanceCosts',
            'seasonalTrends'
        ));
    }

    /**
     * Get total revenue for the tenant.
     */
    private function getTotalRevenue(int $tenantId): float
    {
        return Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->sum('montant_total') ?? 0;
    }

    /**
     * Get revenue growth percentage for current month.
     */
    private function getRevenueGrowth(int $tenantId): float
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $currentMonthRevenue = Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereMonth('date_fin', $currentMonth)
            ->whereYear('date_fin', $currentYear)
            ->sum('montant_total') ?? 0;

        $lastMonthRevenue = Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereMonth('date_fin', $currentMonth - 1)
            ->whereYear('date_fin', $currentYear)
            ->sum('montant_total') ?? 0;

        if ($lastMonthRevenue == 0) return 0;
        
        return round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
    }

    /**
     * Get today's revenue.
     */
    private function getTodayRevenue(int $tenantId): float
    {
        return Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereDate('date_fin', Carbon::today())
            ->sum('montant_total') ?? 0;
    }

    /**
     * Get this week's revenue.
     */
    private function getWeekRevenue(int $tenantId): float
    {
        return Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereBetween('date_fin', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('montant_total') ?? 0;
    }

    /**
     * Get this month's revenue.
     */
    private function getMonthRevenue(int $tenantId): float
    {
        return Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereMonth('date_fin', Carbon::now()->month)
            ->whereYear('date_fin', Carbon::now()->year)
            ->sum('montant_total') ?? 0;
    }

    /**
     * Get revenue chart data for the last 30 days.
     */
    private function getRevenueChartData(int $tenantId): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $revenue = Contrat::where('tenant_id', $tenantId)
                ->where('statut', 'termine')
                ->whereDate('date_fin', $date)
                ->sum('montant_total') ?? 0;
            
            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get active rentals count.
     */
    private function getActiveRentals(int $tenantId): int
    {
        return Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'en_cours')
            ->count();
    }

    /**
     * Get available vehicles count.
     */
    private function getAvailableVehicles(int $tenantId): int
    {
        return Vehicule::where('tenant_id', $tenantId)
            ->where('statut', 'disponible')
            ->count();
    }

    /**
     * Get fleet utilization percentage.
     */
    private function getFleetUtilization(int $tenantId): float
    {
        $totalVehicles = Vehicule::where('tenant_id', $tenantId)->count();
        $rentedVehicles = Vehicule::where('tenant_id', $tenantId)
            ->where('statut', 'en_location')
            ->count();

        if ($totalVehicles == 0) return 0;
        
        return round(($rentedVehicles / $totalVehicles) * 100, 1);
    }

    /**
     * Get fleet status breakdown.
     */
    private function getFleetStatus(int $tenantId): array
    {
        $statuses = ['disponible', 'en_location', 'en_maintenance', 'hors_service'];
        $result = [];

        foreach ($statuses as $status) {
            $result[$status] = Vehicule::where('tenant_id', $tenantId)
                ->where('statut', $status)
                ->count();
        }

        return $result;
    }

    /**
     * Get popular vehicle categories.
     */
    private function getPopularCategories(int $tenantId): array
    {
        // Since the 'categorie' column doesn't exist, we'll use vehicle brands instead
        $categories = Vehicule::where('vehicules.tenant_id', $tenantId)
            ->join('contrats', 'vehicules.id', '=', 'contrats.vehicule_id')
            ->join('marques', 'vehicules.marque_id', '=', 'marques.id')
            ->where('contrats.statut', 'termine')
            ->select('marques.nom as category_name', DB::raw('COUNT(*) as rental_count'))
            ->groupBy('marques.nom')
            ->orderBy('rental_count', 'desc')
            ->limit(5)
            ->get();

        $totalRentals = $categories->sum('rental_count');

        foreach ($categories as $category) {
            $category->percentage = $totalRentals > 0 ? round(($category->rental_count / $totalRentals) * 100, 1) : 0;
        }

        return $categories->toArray();
    }

    /**
     * Get top performing vehicles.
     */
    private function getTopPerformingVehicles(int $tenantId): array
    {
        return Vehicule::where('vehicules.tenant_id', $tenantId)
            ->join('contrats', 'vehicules.id', '=', 'contrats.vehicule_id')
            ->join('marques', 'vehicules.marque_id', '=', 'marques.id')
            ->where('contrats.statut', 'termine')
            ->select(
                'vehicules.id',
                'marques.nom as brand_name',
                'vehicules.modele',
                'vehicules.immatriculation',
                DB::raw('COUNT(contrats.id) as rental_count'),
                DB::raw('SUM(contrats.montant_total) as total_revenue')
            )
            ->groupBy('vehicules.id', 'marques.nom', 'vehicules.modele', 'vehicules.immatriculation')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Get customer satisfaction score.
     */
    private function getCustomerSatisfaction(int $tenantId): float
    {
        // This would typically come from customer reviews/ratings
        // For now, returning a placeholder value
        return 4.2;
    }

    /**
     * Get total customers count.
     */
    private function getTotalCustomers(int $tenantId): int
    {
        return Client::where('tenant_id', $tenantId)->count();
    }

    /**
     * Get new customers this month.
     */
    private function getNewCustomersThisMonth(int $tenantId): int
    {
        return Client::where('tenant_id', $tenantId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    /**
     * Get repeat customers count.
     */
    private function getRepeatCustomers(int $tenantId): int
    {
        return Client::where('clients.tenant_id', $tenantId)
            ->join('contrats', 'clients.id', '=', 'contrats.client_id')
            ->groupBy('clients.id')
            ->having(DB::raw('COUNT(contrats.id)'), '>', 1)
            ->count();
    }

    /**
     * Get average rental duration in days.
     */
    private function getAverageRentalDuration(int $tenantId): float
    {
        $rentals = Contrat::where('tenant_id', $tenantId)
            ->where('statut', 'termine')
            ->whereNotNull('date_debut')
            ->whereNotNull('date_fin')
            ->get();

        if ($rentals->isEmpty()) return 0;

        $totalDays = 0;
        foreach ($rentals as $rental) {
            $totalDays += Carbon::parse($rental->date_debut)->diffInDays($rental->date_fin);
        }

        return round($totalDays / $rentals->count(), 1);
    }

    /**
     * Get maintenance costs by period.
     */
    private function getMaintenanceCosts(int $tenantId): array
    {
        $monthly = $this->getMaintenanceCostsForPeriod($tenantId, 'month');
        $quarterly = $this->getMaintenanceCostsForPeriod($tenantId, 'quarter');
        $yearly = $this->getMaintenanceCostsForPeriod($tenantId, 'year');

        return [
            'monthly' => $monthly,
            'quarterly' => $quarterly,
            'yearly' => $yearly
        ];
    }

    /**
     * Get maintenance costs for a specific period.
     */
    private function getMaintenanceCostsForPeriod(int $tenantId, string $period): float
    {
        $query = Charge::where('charges.tenant_id', $tenantId)
            ->join('vehicules', 'charges.vehicule_id', '=', 'vehicules.id');

        switch ($period) {
            case 'month':
                $query->whereMonth('charges.date', Carbon::now()->month)
                      ->whereYear('charges.date', Carbon::now()->year);
                break;
            case 'quarter':
                $query->whereBetween('charges.date', [
                    Carbon::now()->startOfQuarter(),
                    Carbon::now()->endOfQuarter()
                ]);
                break;
            case 'year':
                $query->whereYear('charges.date', Carbon::now()->year);
                break;
        }

        return $query->sum('charges.montant') ?? 0;
    }

    /**
     * Get seasonal trends.
     */
    private function getSeasonalTrends(int $tenantId): array
    {
        // Analyze rental patterns by month to determine seasonal trends
        $monthlyRentals = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyRentals[$month] = Contrat::where('tenant_id', $tenantId)
                ->where('statut', 'termine')
                ->whereMonth('date_debut', $month)
                ->whereYear('date_debut', Carbon::now()->year)
                ->count();
        }

        $peakMonth = array_search(max($monthlyRentals), $monthlyRentals);
        $lowMonth = array_search(min($monthlyRentals), $monthlyRentals);
        
        $peakFactor = max($monthlyRentals) > 0 ? round(max($monthlyRentals) / min($monthlyRentals), 1) : 1;

        $monthNames = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return [
            'peak_month' => $monthNames[$peakMonth] ?? 'N/A',
            'low_month' => $monthNames[$lowMonth] ?? 'N/A',
            'peak_factor' => $peakFactor
        ];
    }

    /**
     * Display customer analysis report.
     */
    public function customers(): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $customers = Client::where('tenant_id', $tenantId)
            ->withCount(['contrats' => function($query) {
                $query->where('statut', 'termine');
            }])
            ->withSum(['contrats' => function($query) {
                $query->where('statut', 'termine');
            }], 'montant_total')
            ->orderBy('contrats_count', 'desc')
            ->paginate(20);

        return view('reports.customers', compact('customers'));
    }

    /**
     * Display maintenance report.
     */
    public function maintenance(): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $maintenanceRecords = Charge::where('charges.tenant_id', $tenantId)
            ->with(['vehicule.marque'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        $monthlyCosts = $this->getMonthlyMaintenanceCosts($tenantId);
        $vehicleMaintenanceSummary = $this->getVehicleMaintenanceSummary($tenantId);

        return view('reports.maintenance', compact('maintenanceRecords', 'monthlyCosts', 'vehicleMaintenanceSummary'));
    }

    /**
     * Display seasonal trends report.
     */
    public function seasonal(): View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $seasonalData = $this->getDetailedSeasonalData($tenantId);
        $yearlyComparison = $this->getYearlyComparison($tenantId);

        return view('reports.seasonal', compact('seasonalData', 'yearlyComparison'));
    }

    /**
     * Export financial report.
     */
    public function exportFinancial(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        $period = $request->get('period', 'month');
        
        $data = $this->generateFinancialReport($tenantId, $period);
        
        $filename = "rapport_financier_{$period}_" . date('Y-m-d') . ".csv";
        
        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export operational report.
     */
    public function exportOperational(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $data = $this->generateOperationalReport($tenantId);
        
        $filename = "rapport_operationnel_" . date('Y-m-d') . ".csv";
        
        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export customer report.
     */
    public function exportCustomers(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $data = $this->generateCustomerReport($tenantId);
        
        $filename = "rapport_clients_" . date('Y-m-d') . ".csv";
        
        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export maintenance report.
     */
    public function exportMaintenance(Request $request): Response
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to 1 if no tenant_id
        
        $data = $this->generateMaintenanceReport($tenantId);
        
        $filename = "rapport_maintenance_" . date('Y-m-d') . ".csv";
        
        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Generate financial report data.
     */
    private function generateFinancialReport(int $tenantId, string $period): string
    {
        $headers = ['Date', 'Revenus (€)', 'Nombre de locations', 'Revenu moyen par location (€)'];
        $data = [];

        // Implementation for generating financial report CSV data
        // This would include detailed revenue breakdown by date

        $csv = implode(',', $headers) . "\n";
        foreach ($data as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    /**
     * Generate operational report data.
     */
    private function generateOperationalReport(int $tenantId): string
    {
        $headers = ['Véhicule', 'Statut', 'Utilisation (%)', 'Revenus générés (€)', 'Nombre de locations'];
        $data = [];

        // Implementation for generating operational report CSV data
        // This would include fleet utilization and performance metrics

        $csv = implode(',', $headers) . "\n";
        foreach ($data as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    /**
     * Generate customer report data.
     */
    private function generateCustomerReport(int $tenantId): string
    {
        $headers = ['Client', 'Email', 'Téléphone', 'Nombre de locations', 'Total dépensé (€)', 'Dernière location'];
        $data = [];

        // Implementation for generating customer report CSV data
        // This would include customer behavior and spending patterns

        $csv = implode(',', $headers) . "\n";
        foreach ($data as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    /**
     * Generate maintenance report data.
     */
    private function generateMaintenanceReport(int $tenantId): string
    {
        $headers = ['Véhicule', 'Type de charge', 'Date', 'Montant (€)', 'Description'];
        $data = [];

        // Implementation for generating maintenance report CSV data
        // This would include all maintenance costs and charges

        $csv = implode(',', $headers) . "\n";
        foreach ($data as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    /**
     * Get monthly maintenance costs for the current year.
     */
    private function getMonthlyMaintenanceCosts(int $tenantId): array
    {
        $costs = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $costs[$month] = Charge::where('charges.tenant_id', $tenantId)
                ->join('vehicules', 'charges.vehicule_id', '=', 'vehicules.id')
                ->whereMonth('charges.date', $month)
                ->whereYear('charges.date', Carbon::now()->year)
                ->sum('charges.montant') ?? 0;
        }

        return $costs;
    }

    /**
     * Get vehicle maintenance summary.
     */
    private function getVehicleMaintenanceSummary(int $tenantId): array
    {
        return Vehicule::where('vehicules.tenant_id', $tenantId)
            ->leftJoin('charges', 'vehicules.id', '=', 'charges.vehicule_id')
            ->join('marques', 'vehicules.marque_id', '=', 'marques.id')
            ->select(
                'vehicules.id',
                'marques.nom as brand_name',
                'vehicules.modele',
                'vehicules.immatriculation',
                DB::raw('COUNT(charges.id) as maintenance_count'),
                DB::raw('SUM(charges.montant) as total_cost')
            )
            ->groupBy('vehicules.id', 'marques.nom', 'vehicules.modele', 'vehicules.immatriculation')
            ->orderBy('total_cost', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get detailed seasonal data.
     */
    private function getDetailedSeasonalData(int $tenantId): array
    {
        $data = [];
        $monthNames = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        for ($month = 1; $month <= 12; $month++) {
            $data[$month] = [
                'month_name' => $monthNames[$month],
                'rentals' => Contrat::where('tenant_id', $tenantId)
                    ->where('statut', 'termine')
                    ->whereMonth('date_debut', $month)
                    ->whereYear('date_debut', Carbon::now()->year)
                    ->count(),
                'revenue' => Contrat::where('tenant_id', $tenantId)
                    ->where('statut', 'termine')
                    ->whereMonth('date_debut', $month)
                    ->whereYear('date_debut', Carbon::now()->year)
                    ->sum('montant_total') ?? 0
            ];
        }

        return $data;
    }

    /**
     * Get yearly comparison data.
     */
    private function getYearlyComparison(int $tenantId): array
    {
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;

        $currentYearData = $this->getYearlyData($tenantId, $currentYear);
        $lastYearData = $this->getYearlyData($tenantId, $lastYear);

        return [
            'current_year' => $currentYearData,
            'last_year' => $lastYearData,
            'growth' => $this->calculateYearOverYearGrowth($currentYearData, $lastYearData)
        ];
    }

    /**
     * Get yearly data for a specific year.
     */
    private function getYearlyData(int $tenantId, int $year): array
    {
        return [
            'total_revenue' => Contrat::where('tenant_id', $tenantId)
                ->where('statut', 'termine')
                ->whereYear('date_fin', $year)
                ->sum('montant_total') ?? 0,
            'total_rentals' => Contrat::where('tenant_id', $tenantId)
                ->where('statut', 'termine')
                ->whereYear('date_fin', $year)
                ->count(),
            'new_customers' => Client::where('tenant_id', $tenantId)
                ->whereYear('created_at', $year)
                ->count()
        ];
    }

    /**
     * Calculate year-over-year growth.
     */
    private function calculateYearOverYearGrowth(array $current, array $last): array
    {
        $growth = [];
        
        foreach ($current as $key => $value) {
            if (isset($last[$key]) && $last[$key] > 0) {
                $growth[$key] = round((($value - $last[$key]) / $last[$key]) * 100, 1);
            } else {
                $growth[$key] = 0;
            }
        }

        return $growth;
    }
}
