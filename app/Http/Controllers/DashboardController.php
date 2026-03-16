<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\GallonContainer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->get('tenant');
        
        if (!$tenant) {
            return redirect()->route('tenant.select');
        }

        // Get current date ranges
        $today = now();
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // Enhanced dashboard statistics
        $stats = [
            'total_customers' => $tenant->getCustomerCount(),
            'active_customers' => $tenant->customers()->where('is_active', true)->count(),
            'new_customers_this_month' => $tenant->customers()->where('created_at', '>=', $thisMonthStart)->count(),
            'active_products' => $tenant->products()->where('is_active', true)->count(),
            'monthly_revenue' => $tenant->getMonthlyRevenue(),
            'last_month_revenue' => $tenant->sales()->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('total_amount'),
            'revenue_growth' => 0, // Will calculate below
            'pending_deliveries' => $tenant->deliveries()->where('status', 'pending')->count(),
            'deliveries_today' => $tenant->deliveries()->whereDate('scheduled_at', $today)->count(),
            'completed_deliveries_today' => $tenant->deliveries()->whereDate('delivered_at', $today)->count(),
            'low_stock_products' => $tenant->products()->where('stock_quantity', '<=', 'minimum_stock')->count(),
            'containers_with_customers' => $tenant->gallonContainers()->where('status', 'with_customer')->count(),
            'total_containers' => $tenant->gallonContainers()->count(),
            'today_sales' => $tenant->sales()->whereDate('created_at', $today)->count(),
            'today_revenue' => $tenant->sales()->whereDate('created_at', $today)->sum('total_amount'),
            'pending_payments' => $tenant->sales()->where('payment_status', 'pending')->sum('total_amount'),
            'total_outstanding_balance' => $tenant->customers()->sum('outstanding_balance'),
        ];

        // Calculate revenue growth
        if ($stats['last_month_revenue'] > 0) {
            $stats['revenue_growth'] = (($stats['monthly_revenue'] - $stats['last_month_revenue']) / $stats['last_month_revenue']) * 100;
        }

        // Get recent sales with enhanced data
        $recentSales = $tenant->sales()
            ->with(['customer', 'user', 'saleItems.product'])
            ->latest()
            ->take(10)
            ->get();

        // Get pending deliveries with enhanced data
        $pendingDeliveries = $tenant->deliveries()
            ->with(['customer', 'deliveryPerson', 'sale'])
            ->where('status', 'pending')
            ->orderBy('scheduled_at', 'asc')
            ->take(10)
            ->get();

        // Get low stock products with urgency levels
        $lowStockProducts = $tenant->products()
            ->where('stock_quantity', '<=', 'minimum_stock')
            ->orderByRaw('stock_quantity / minimum_stock ASC')
            ->take(10)
            ->get();

        // Enhanced sales chart data for the last 30 days
        $salesChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daySales = $tenant->sales()->whereDate('created_at', $date);
            
            $salesChart[] = [
                'date' => $date->format('M d'),
                'sales' => $daySales->sum('total_amount'),
                'orders' => $daySales->count(),
                'customers' => $daySales->distinct('customer_id')->count('customer_id'),
            ];
        }

        // Top customers by revenue this month
        $topCustomers = $tenant->customers()
            ->with(['sales' => function ($query) use ($thisMonthStart) {
                $query->where('created_at', '>=', $thisMonthStart);
            }])
            ->get()
            ->map(function ($customer) {
                $customer->monthly_revenue = $customer->sales->sum('total_amount');
                return $customer;
            })
            ->sortByDesc('monthly_revenue')
            ->take(5);

        // Recent activities
        $recentActivities = collect();
        
        // Add recent sales
        $recentSales->take(5)->each(function ($sale) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'sale',
                'description' => "New sale: ₱" . number_format($sale->total_amount, 2) . " for " . $sale->customer->name,
                'user' => $sale->user->name,
                'time' => $sale->created_at->diffForHumans(),
                'icon' => 'bi-cash-coin',
                'color' => '#10b981'
            ]);
        });

        // Add recent deliveries
        $tenant->deliveries()->with('customer')->latest()->take(5)->get()->each(function ($delivery) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'delivery',
                'description' => "Delivery " . ucfirst($delivery->status) . " for " . $delivery->customer->name,
                'user' => $delivery->deliveryPerson?->name ?? 'System',
                'time' => $delivery->updated_at->diffForHumans(),
                'icon' => 'bi-truck',
                'color' => '#0ea5e9'
            ]);
        });

        // Add new customers
        $tenant->customers()->latest()->take(3)->get()->each(function ($customer) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'customer',
                'description' => "New customer registered: " . $customer->name,
                'user' => $customer->name,
                'time' => $customer->created_at->diffForHumans(),
                'icon' => 'bi-person-plus',
                'color' => '#f59e0b'
            ]);
        });

        // Sort activities by time
        $recentActivities = $recentActivities->sortByDesc(function ($activity) {
            // Convert diffForHumans to timestamp for sorting
            return now()->sub(
                str_replace([' ago', ' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day'], ['', 's', 's', 'm', 'm', 'h', 'h', 'd', 'd'], $activity['time'])
            )->timestamp;
        })->take(8);

        return view('dashboard.index', compact(
            'tenant',
            'stats',
            'recentSales',
            'pendingDeliveries',
            'lowStockProducts',
            'salesChart',
            'topCustomers',
            'recentActivities'
        ));
    }

    public function selectTenant()
    {
        $tenants = Tenant::where('is_active', true)->get();
        return view('tenant.select', compact('tenants'));
    }

    public function setTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id'
        ]);

        $tenant = Tenant::find($request->tenant_id);
        session(['tenant_id' => $tenant->id]);

        return redirect()->route('dashboard');
    }
}
