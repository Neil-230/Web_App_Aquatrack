@extends('layouts.app')

@section('title', 'Dashboard - AquaTrack')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Modern Header -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="fw-bold tracking-tight text-slate-800" style="font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Dashboard
            </h1>
            <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 400;">
                Overview of <span class="text-primary fw-medium">{{ $tenant->name }}</span> station operations
            </p>
        </div>
        <div class="text-end">
            <div class="badge rounded-pill px-3 py-2" style="background: rgba(14, 165, 233, 0.08); color: var(--primary-color); border: 1px solid rgba(14, 165, 233, 0.15); font-weight: 600; font-size: 0.875rem;">
                <i class="bi bi-clock-history me-1"></i> {{ now()->format('M d, Y') }}
            </div>
        </div>
    </div>

    @php
        $currentPlan = $tenant->subscriptionPlan->name ?? 'Basic';
    @endphp

    <!-- Plan Status - Minimalist Banner -->
    <div class="card border-0 shadow-sm mb-5 p-4" style="border-radius: 24px; background: #ffffff;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="p-2 rounded-3" style="background: #f0f9ff; color: var(--primary-color);">
                        <i class="bi bi-stars" style="font-size: 1.25rem;"></i>
                    </span>
                    <h2 class="h5 fw-bold mb-0">Subscription Plan</h2>
                </div>
                <p class="text-slate-500 mb-0">
                    You're currently on <strong>{{ $currentPlan }}</strong>. Upgrade to unlock advanced analytics, automated delivery optimization, and priority support.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex gap-2 justify-content-lg-end">
                    <button class="btn btn-primary px-4" style="border-radius: 12px; font-weight: 600;">
                        Upgrade Plan
                    </button>
                    <button class="btn btn-outline-secondary px-3" style="border-radius: 12px; font-weight: 600;">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Stats Grid -->
    <div class="row g-4 mb-5" id="dashboard-content">
        <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 24px;">
                <div class="text-slate-400 mb-2"><i class="bi bi-people" style="font-size: 1.5rem;"></i></div>
                <div class="fw-bold text-slate-800 mb-1" style="font-size: 1.75rem;">{{ $stats['total_customers'] }}</div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Total Customers</div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 24px;">
                <div class="text-primary mb-2"><i class="bi bi-cash-stack" style="font-size: 1.5rem;"></i></div>
                <div class="fw-bold text-slate-800 mb-1" style="font-size: 1.5rem;">₱{{ number_format($stats['monthly_revenue'], 0) }}</div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Monthly Revenue</div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 24px;">
                <div class="text-success mb-2"><i class="bi bi-receipt" style="font-size: 1.5rem;"></i></div>
                <div class="fw-bold text-slate-800 mb-1" style="font-size: 1.75rem;">{{ $stats['today_sales'] }}</div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Today's Sales</div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 24px;">
                <div class="text-warning mb-2"><i class="bi bi-truck" style="font-size: 1.5rem;"></i></div>
                <div class="fw-bold text-slate-800 mb-1" style="font-size: 1.75rem;">{{ $stats['pending_deliveries'] }}</div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Pending Deliveries</div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 24px;">
                <div class="text-danger mb-2"><i class="bi bi-exclamation-triangle" style="font-size: 1.5rem;"></i></div>
                <div class="fw-bold text-slate-800 mb-1" style="font-size: 1.75rem;">{{ $stats['low_stock_products'] }}</div>
                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Low Stock</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Modern Recent Sales Table -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="h5 fw-bold mb-0">Recent Sales</h3>
                    <a href="{{ route('sales.index') }}" class="text-primary fw-semibold text-decoration-none small">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase fw-bold">
                                <th class="border-0 px-0">Invoice</th>
                                <th class="border-0">Customer</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0 text-end px-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="px-0 py-3 fw-bold text-slate-800">#{{ $sale->invoice_number }}</td>
                                    <td>
                                        <div class="fw-medium text-slate-700">{{ $sale->customer->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $sale->created_at->format('M d, g:i A') }}</div>
                                    </td>
                                    <td class="fw-bold text-slate-800">₱{{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="text-end px-0">
                                        <span class="badge {{ $sale->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-3 py-2 rounded-pill" style="font-size: 0.7rem; font-weight: 700;">
                                            {{ strtoupper($sale->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No recent sales found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modern Recent Activities -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px;">
                <h3 class="h5 fw-bold mb-4">Activities</h3>
                <div class="d-flex flex-column gap-4">
                    @forelse($recentActivities as $activity)
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <span class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: {{ $activity['color'] }}15; color: {{ $activity['color'] }};">
                                    <i class="bi {{ $activity['icon'] }}"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-slate-700 fw-medium mb-1" style="font-size: 0.9rem;">{{ $activity['description'] }}</div>
                                <div class="text-muted small">{{ $activity['time'] }} • {{ $activity['user'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">No recent activities</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Sales Performance Chart -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="h5 fw-bold mb-1">Performance</h3>
                <p class="text-muted small mb-0">Daily revenue over the last 14 days</p>
            </div>
            <div class="text-end">
                <div class="text-muted small fw-medium uppercase">30D Revenue</div>
                <div class="h4 fw-bold text-primary mb-0">₱{{ number_format(array_sum(array_column($salesChart, 'sales')), 0) }}</div>
            </div>
        </div>
        
        <div class="d-flex align-items-end justify-content-between px-2" style="height: 200px; gap: 8px;">
            @php
                $chartSlice = array_slice($salesChart, -14);
                $maxSales = max(array_sum(array_column($chartSlice, 'sales')), 1);
                // Actually need max of individual daily sales for scale
                $maxDaily = max(array_column($chartSlice, 'sales')) ?: 1;
            @endphp
            
            @foreach($chartSlice as $data)
                @php
                    $height = ($data['sales'] / $maxDaily) * 100;
                    $height = max($height, 5); // Minimum height for visibility
                @endphp
                <div class="flex-grow-1 d-flex flex-column align-items-center group" style="max-width: 40px;">
                    <div class="w-100 rounded-top" 
                         style="height: {{ $height }}%; background: #e0f2fe; transition: all 0.3s ease; cursor: pointer;"
                         onmouseover="this.style.background='var(--primary-color)'"
                         onmouseout="this.style.background='#e0f2fe'"
                         title="₱{{ number_format($data['sales'], 0) }}">
                    </div>
                    <div class="mt-3 text-muted" style="font-size: 0.65rem; font-weight: 600;">{{ $data['date'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-500 { color: #64748b; }
    .text-slate-400 { color: #94a3b8; }
    .bg-success-subtle { background-color: #f0fdf4; }
    .bg-warning-subtle { background-color: #fffbeb; }
</style>
@endsection
