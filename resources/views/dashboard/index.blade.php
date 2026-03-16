@extends('layouts.app')

@section('title', 'Dashboard - AquaTrack')

@section('content')
<div class="container">
    <!-- Enhanced Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </h1>
            <p style="color: var(--text-secondary); font-size: 1rem;">
                Real-time overview of your water refilling station operations.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 0.625rem 1.25rem; border-radius: var(--radius); font-weight: 600; box-shadow: var(--shadow-sm); display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="bi bi-building"></i>
                {{ $tenant->name }}
            </span>
        </div>
    </div>

    @php
        $currentPlan = $tenant->subscriptionPlan->name ?? 'Basic';
    @endphp

    <!-- Plan Selection Banner -->
    <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
            <div>
                <div class="card-header" style="margin-bottom: 0.5rem;">
                    <i class="bi bi-stars"></i>
                    <span>Choose Your Plan</span>
                </div>
                <p style="color: var(--text-secondary); margin-bottom: 0;">
                    You're currently on <strong>{{ $currentPlan }}</strong>. Pick a plan to unlock the best tools for your station.
                </p>
            </div>
            <a href="#dashboard-content" class="btn btn-secondary">
                <i class="bi bi-arrow-down"></i>
                Continue to Dashboard
            </a>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-12 col-lg-6">
                <div class="p-4 border rounded-3 h-100" style="background: var(--secondary-color);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="h5 mb-1">Basic Plan</h3>
                            <div class="text-muted">Core tracking and daily ops</div>
                        </div>
                        <span class="badge text-bg-secondary">Recommended</span>
                    </div>
                    <ul class="mt-3 mb-0 text-sm" style="padding-left: 1.25rem;">
                        <li>Customer and sales tracking</li>
                        <li>Daily dashboard metrics</li>
                        <li>Standard reports</li>
                    </ul>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Continue with Basic
                        </button>
                        <span class="text-muted small align-self-center">Perfect for small stations</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="p-4 border rounded-3 h-100" style="background: linear-gradient(135deg, #f8fafc, #eef2ff); border-color: #c7d2fe;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="h5 mb-1">Premium Plan</h3>
                            <div class="text-muted">Automation, analytics, priority support</div>
                        </div>
                        <span class="badge text-bg-primary">Premium</span>
                    </div>
                    <ul class="mt-3 mb-0 text-sm" style="padding-left: 1.25rem;">
                        <li>Advanced insights and alerts</li>
                        <li>Delivery optimization</li>
                        <li>Priority support</li>
                    </ul>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <button class="btn btn-secondary">
                            <i class="bi bi-rocket-takeoff"></i>
                            Upgrade to Premium
                        </button>
                        <span class="text-muted small align-self-center">Best for growing stations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Grid with Functional Metrics -->
    <div class="stats-grid" id="dashboard-content">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">{{ $stats['total_customers'] }}</div>
                    <div class="stat-label">Total Customers</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-people"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                {{ $stats['active_customers'] }} active • {{ $stats['new_customers_this_month'] }} new this month
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">₱{{ number_format($stats['monthly_revenue'], 0) }}</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                @if($stats['revenue_growth'] > 0)
                    <span style="color: var(--success-color);">↑ {{ number_format($stats['revenue_growth'], 1) }}% from last month</span>
                @else
                    <span style="color: var(--accent-color);">↓ {{ number_format(abs($stats['revenue_growth']), 1) }}% from last month</span>
                @endif
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">{{ $stats['today_sales'] }}</div>
                    <div class="stat-label">Today's Sales</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-receipt"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                ₱{{ number_format($stats['today_revenue'], 2) }} revenue today
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">{{ $stats['pending_deliveries'] }}</div>
                    <div class="stat-label">Pending Deliveries</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-truck"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                {{ $stats['deliveries_today'] }} scheduled today • {{ $stats['completed_deliveries_today'] }} completed
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">{{ $stats['low_stock_products'] }}</div>
                    <div class="stat-label">Low Stock Alert</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                Products need immediate restocking
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div class="stat-value">{{ $stats['containers_with_customers'] }}</div>
                    <div class="stat-label">Containers Out</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.8;"><i class="bi bi-droplet"></i></div>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">
                {{ $stats['total_containers'] }} total containers in system
            </div>
        </div>
    </div>

    <!-- Financial Overview Section -->
    <div class="row g-4 mb-4">
        <!-- Pending Payments -->
        <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-wallet2"></i>
                <span>Payment Overview</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="padding: 1rem; background: var(--secondary-color); border-radius: var(--radius); text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent-color);">
                        ₱{{ number_format($stats['pending_payments'], 2) }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">
                        Pending Payments
                    </div>
                </div>
                <div style="padding: 1rem; background: var(--secondary-color); border-radius: var(--radius); text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent-color);">
                        ₱{{ number_format($stats['total_outstanding_balance'], 2) }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">
                        Outstanding Balances
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Top Customers -->
        <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy"></i>
                <span>Top Customers This Month</span>
            </div>
            @if($topCustomers->count() > 0)
                <div style="space-y: 0.75rem;">
                    @foreach($topCustomers as $index => $customer)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--secondary-color); border-radius: var(--radius-sm);">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.75rem;">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">{{ $customer->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ $customer->sales->count() }} orders
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: var(--primary-color);">
                                    ₱{{ number_format($customer->monthly_revenue, 0) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    No customer data available for this month
                </div>
            @endif
        </div>
        </div>
    </div>

    <!-- Enhanced Recent Sales & Activities -->
    <div class="row g-4 mb-4">
        <!-- Recent Sales -->
        <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Recent Sales</span>
            </div>
            @if($recentSales->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--primary-color);">#{{ $sale->invoice_number }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $sale->customer->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ $sale->created_at->format('M d, g:i A') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem; color: var(--text-primary);">
                                        {{ $sale->saleItems->count() }} items
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        by {{ $sale->user->name }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);">
                                        ₱{{ number_format($sale->total_amount, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <span style="background: {{ $sale->payment_status === 'paid' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #f59e0b, #d97706)' }}; color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; box-shadow: var(--shadow-sm);">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 1rem; text-align: center;">
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary" style="font-size: 0.875rem;">
                        View All Sales →
                    </a>
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"><i class="bi bi-receipt"></i></div>
                    <h3 style="color: var(--text-secondary); margin-bottom: 0.5rem;">No recent sales</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                        Start recording sales to see them here
                    </p>
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Record First Sale
                    </a>
                </div>
            @endif
        </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-12 col-xl-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bell"></i>
                <span>Recent Activities</span>
            </div>
            @if($recentActivities->count() > 0)
                <div style="space-y: 1rem;">
                    @foreach($recentActivities as $activity)
                        <div style="display: flex; gap: 0.75rem; padding: 0.75rem; background: var(--secondary-color); border-radius: var(--radius-sm); border-left: 3px solid {{ $activity['color'] }};">
                            <div style="font-size: 1.25rem;"><i class="bi {{ $activity['icon'] }}"></i></div>
                            <div style="flex: 1;">
                                <div style="font-size: 0.875rem; color: var(--text-primary); margin-bottom: 0.25rem;">
                                    {{ $activity['description'] }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    {{ $activity['user'] }} • {{ $activity['time'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    No recent activities
                </div>
            @endif
        </div>
        </div>
    </div>

    <!-- Enhanced Sales Chart with 30-day data -->
    <div class="card">
        <div class="card-header">
            <i class="bi bi-graph-up-arrow"></i>
            <span>30-Day Sales Performance</span>
        </div>
        <div style="height: 250px; display: flex; align-items: flex-end; justify-content: space-between; padding: 1rem; position: relative;">
            <!-- Chart Background Grid -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 2rem; display: flex; flex-direction: column; justify-content: space-between; pointer-events: none;">
                @for($i = 0; $i <= 4; $i++)
                    <div style="border-bottom: 1px solid var(--border-color); opacity: 0.3;"></div>
                @endfor
            </div>
            
            <!-- Chart Bars - Show last 14 days for better visibility -->
            @foreach(array_slice($salesChart, -14) as $index => $data)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; margin: 0 0.25rem; position: relative; z-index: 1;">
                    <div style="width: 100%; max-width: 25px; background: linear-gradient(180deg, var(--primary-light) 0%, var(--primary-color) 100%); border-radius: 2px 2px 0 0; height: {{ $data['sales'] > 0 ? ($data['sales'] / max(array_column(array_slice($salesChart, -14), 'sales')) * 180) : 4 }}px; min-height: 4px; transition: all 0.3s; cursor: pointer; position: relative;"
                         onmouseover="this.style.transform='scaleY(1.05)'" 
                         onmouseout="this.style.transform='scaleY(1)'">
                        <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background: var(--text-primary); color: white; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.625rem; font-weight: 600; white-space: nowrap; opacity: 0; transition: opacity 0.3s; z-index: 10;"
                             onmouseover="this.style.opacity='1'" 
                             onmouseout="this.style.opacity='0'">
                            ₱{{ number_format($data['sales'], 0) }}
                            <br><small>{{ $data['orders'] }} orders</small>
                        </div>
                    </div>
                    <div style="font-size: 0.625rem; color: var(--text-secondary); margin-top: 0.5rem; text-align: center; font-weight: 500;">{{ $data['date'] }}</div>
                </div>
            @endforeach
        </div>
        
        <!-- Enhanced Chart Summary -->
        <div style="padding: 1rem; background: var(--secondary-color); border-radius: var(--radius); margin-top: 1rem;">
            <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Total Revenue (30 days)</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: var(--primary-color);">
                    ₱{{ number_format(array_sum(array_column($salesChart, 'sales')), 2) }}
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Daily Average</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary);">
                    ₱{{ number_format(array_sum(array_column($salesChart, 'sales')) / 30, 2) }}
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Total Orders</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary);">
                    {{ array_sum(array_column($salesChart, 'orders')) }}
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Avg Order Value</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary);">
                    ₱{{ number_format(array_sum(array_column($salesChart, 'sales')) / max(array_sum(array_column($salesChart, 'orders')), 1), 2) }}
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
// Get user settings from session with proper defaults
const defaultSettings = {
    'auto_refresh': true,
    'refresh_interval': 30,
    'show_refresh_status': false
};

const sessionSettings = @json(session('user_settings', []));
const userSettings = Object.assign({}, defaultSettings, sessionSettings);

// Auto-refresh functionality
let refreshInterval;
let isRefreshing = false;
const REFRESH_INTERVAL = userSettings.refresh_interval * 1000; // Convert to milliseconds

function startAutoRefresh() {
    if (userSettings.auto_refresh && REFRESH_INTERVAL > 0) {
        refreshInterval = setInterval(() => {
            refreshDashboard();
        }, REFRESH_INTERVAL);
    }
}

function refreshDashboard() {
    if (isRefreshing) return;
    
    isRefreshing = true;
    
    // Show refresh status if enabled
    if (userSettings.show_refresh_status) {
        const indicator = document.getElementById('refresh-indicator');
        const text = document.getElementById('refresh-text');
        if (indicator) indicator.style.animation = 'spin 1s linear infinite';
        if (text) text.textContent = 'Updating...';
    }
    
    // Fetch updated dashboard data
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML and update the dashboard
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update stats cards
        updateStats(doc);
        
        // Update recent activities
        updateActivities(doc);
        
        // Update sales chart if needed
        updateChart(doc);
        
        // Reset refresh status if enabled
        if (userSettings.show_refresh_status) {
            const indicator = document.getElementById('refresh-indicator');
            const text = document.getElementById('refresh-text');
            if (indicator) indicator.style.animation = '';
            if (text) text.textContent = 'Auto-refresh: ON';
        }
        
        // Show success feedback
        showRefreshFeedback();
    })
    .catch(error => {
        console.error('Dashboard refresh failed:', error);
        if (userSettings.show_refresh_status) {
            const text = document.getElementById('refresh-text');
            if (text) text.textContent = 'Auto-refresh: ERROR';
            setTimeout(() => {
                if (text) text.textContent = 'Auto-refresh: ON';
            }, 3000);
        }
    })
    .finally(() => {
        isRefreshing = false;
    });
}

function updateStats(doc) {
    // Update stat values
    const statValues = doc.querySelectorAll('.stat-value');
    const currentStatValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach((stat, index) => {
        if (currentStatValues[index]) {
            currentStatValues[index].textContent = stat.textContent;
            // Add pulse animation
            currentStatValues[index].style.animation = 'pulse 0.5s ease';
            setTimeout(() => {
                currentStatValues[index].style.animation = '';
            }, 500);
        }
    });
}

function updateActivities(doc) {
    // Update recent activities
    const activitiesContainer = doc.querySelector('.card:has(.card-header span[title="Recent Activities"])');
    const currentActivitiesContainer = document.querySelector('.card:has(.card-header span[title="Recent Activities"])');
    
    if (activitiesContainer && currentActivitiesContainer) {
        currentActivitiesContainer.innerHTML = activitiesContainer.innerHTML;
    }
}

function updateChart(doc) {
    // Update sales chart data
    const chartContainer = doc.querySelector('.card:has(.card-header span[title="30-Day Sales Performance"])');
    const currentChartContainer = document.querySelector('.card:has(.card-header span[title="30-Day Sales Performance"])');
    
    if (chartContainer && currentChartContainer) {
        currentChartContainer.innerHTML = chartContainer.innerHTML;
    }
}

function showRefreshFeedback() {
    // Show subtle refresh notification
    const feedback = document.createElement('div');
    feedback.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    feedback.textContent = '✓ Dashboard updated';
    document.body.appendChild(feedback);
    
    setTimeout(() => {
        feedback.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(feedback);
        }, 300);
    }, 2000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', () => {
    startAutoRefresh();
    
    // Add manual refresh button functionality if status is shown
    if (userSettings.show_refresh_status) {
        const refreshStatus = document.getElementById('refresh-status');
        if (refreshStatus) {
            refreshStatus.style.cursor = 'pointer';
            refreshStatus.addEventListener('click', () => {
                refreshDashboard();
            });
        }
    }
});

// Clean up interval when page is unloaded
window.addEventListener('beforeunload', () => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endsection


