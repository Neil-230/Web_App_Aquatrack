@extends('layouts.app')

@section('title', 'Sales - AquaTrack')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Modern Header -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="fw-bold tracking-tight text-slate-800" style="font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Sales History
            </h1>
            <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 400;">
                Track and manage your <span class="text-primary fw-medium">transaction records</span>
            </p>
        </div>
        <div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 12px; font-weight: 600;">
                <i class="bi bi-cart-plus"></i>
                <span>Record Sale</span>
            </a>
        </div>
    </div>

    <!-- Modern Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-5 p-4" style="border-radius: 24px;">
        <form method="GET" action="{{ route('sales.index') }}" class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-400"></i>
                    <input type="text" name="search" id="search-input" class="form-control border-0 bg-slate-50 ps-5 py-3" 
                           placeholder="Search by invoice or customer name..." 
                           value="{{ request('search') }}"
                           style="border-radius: 16px; font-size: 1rem;">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="payment_status" class="form-select border-0 bg-slate-50 py-3" style="border-radius: 16px;">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('payment_status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-slate-800 w-100 py-3 text-white" style="border-radius: 16px; background: #1e293b; font-weight: 600;">
                    Search Records
                </button>
            </div>
        </form>
    </div>

    <!-- Modern Sales Table -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        @if($sales->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div class="text-slate-500 small fw-medium">
                    Showing <span class="text-slate-800 fw-bold">{{ $sales->firstItem() }}-{{ $sales->lastItem() }}</span> of <span class="text-slate-800 fw-bold">{{ $sales->total() }}</span> transactions
                </div>
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="dot bg-success"></span>
                        <span class="text-slate-500 small fw-bold">₱{{ number_format($sales->where('payment_status', 'paid')->sum('total_amount'), 0) }} PAID</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="dot bg-warning"></span>
                        <span class="text-slate-500 small fw-bold">₱{{ number_format($sales->where('payment_status', 'pending')->sum('total_amount'), 0) }} PENDING</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="border-0 px-2 pb-3">Invoice</th>
                            <th class="border-0 pb-3">Customer Info</th>
                            <th class="border-0 pb-3">Items</th>
                            <th class="border-0 pb-3">Total Amount</th>
                            <th class="border-0 pb-3">Status</th>
                            <th class="border-0 text-end pb-3 px-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td class="px-2 py-3">
                                    <div class="fw-bold text-slate-800 mb-0">#{{ $sale->invoice_number }}</div>
                                    <div class="text-slate-400 small">{{ $sale->created_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm d-flex align-items-center justify-content-center fw-bold rounded-3" 
                                             style="width: 38px; height: 38px; background: #f8fafc; color: #475569; font-size: 0.8rem; border: 1px solid #e2e8f0;">
                                            {{ strtoupper(substr($sale->customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-700">{{ $sale->customer->name }}</div>
                                            <div class="text-slate-400 small">{{ $sale->customer->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-slate-600 fw-medium">{{ $sale->saleItems->count() }} items</div>
                                    <div class="text-slate-400 small">Processed by {{ $sale->user->name }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-800" style="font-size: 1.1rem;">₱{{ number_format($sale->total_amount, 2) }}</div>
                                    @if($sale->payment_method)
                                        <div class="text-slate-400 small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">via {{ $sale->payment_method }}</div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'paid' => 'bg-success-subtle text-success',
                                            'pending' => 'bg-warning-subtle text-warning',
                                            'overdue' => 'bg-danger-subtle text-danger'
                                        ][$sale->payment_status] ?? 'bg-slate-100 text-slate-500';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} px-3 py-2" style="font-size: 0.7rem; font-weight: 700;">
                                        {{ strtoupper($sale->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-end px-2">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-white border shadow-sm px-3 py-2" style="border-radius: 12px; background: white;">
                                        <i class="bi bi-file-earmark-text text-slate-600"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $sales->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4 text-slate-200">
                    <i class="bi bi-receipt" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold text-slate-800">No sales records found</h4>
                <p class="text-slate-500 mb-4">You haven't recorded any sales yet or no results match your search.</p>
                <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                    Record Your First Sale
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .text-slate-500 { color: #64748b; }
    .text-slate-400 { color: #94a3b8; }
    .bg-success-subtle { background-color: #f0fdf4; }
    .bg-warning-subtle { background-color: #fffbeb; }
    .bg-danger-subtle { background-color: #fef2f2; }
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .group:hover .w-100 { transform: scaleY(1.05); }
</style>
@endsection
