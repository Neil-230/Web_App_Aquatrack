@extends('layouts.app')

@section('title', 'Customers - AquaTrack')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Modern Header -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="fw-bold tracking-tight text-slate-800" style="font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Customers
            </h1>
            <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 400;">
                Manage and track your <span class="text-primary fw-medium">customer relationships</span>
            </p>
        </div>
        <div>
            <a href="{{ route('customers.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 12px; font-weight: 600;">
                <i class="bi bi-plus-lg"></i>
                <span>Add Customer</span>
            </a>
        </div>
    </div>

    <!-- Modern Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-5 p-4" style="border-radius: 24px;">
        <form method="GET" action="{{ route('customers.index') }}" class="row g-3 align-items-center">
            <div class="col-lg-7">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-400"></i>
                    <input type="text" name="search" id="search-input" class="form-control border-0 bg-slate-50 ps-5 py-3" 
                           placeholder="Search by name, email, or phone..." 
                           value="{{ request('search') }}"
                           style="border-radius: 16px; font-size: 1rem;">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="status" id="status-filter" class="form-select border-0 bg-slate-50 py-3" style="border-radius: 16px;">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-slate-800 w-100 py-3 text-white" style="border-radius: 16px; background: #1e293b; font-weight: 600;">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Modern Customers Table -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        @if($customers->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div class="text-slate-500 small fw-medium">
                    Showing <span class="text-slate-800 fw-bold">{{ $customers->firstItem() }}-{{ $customers->lastItem() }}</span> of <span class="text-slate-800 fw-bold">{{ $customers->total() }}</span> customers
                </div>
                <div class="d-flex gap-2">
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2" style="font-size: 0.75rem; font-weight: 700;">
                        {{ $customers->where('is_active', true)->count() }} ACTIVE
                    </span>
                    <span class="badge rounded-pill bg-slate-100 text-slate-500 px-3 py-2" style="font-size: 0.75rem; font-weight: 700;">
                        {{ $customers->where('is_active', false)->count() }} INACTIVE
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="border-0 px-2 pb-3">Customer</th>
                            <th class="border-0 pb-3">Contact</th>
                            <th class="border-0 pb-3">Gallons</th>
                            <th class="border-0 pb-3">Balance</th>
                            <th class="border-0 pb-3">Status</th>
                            <th class="border-0 text-end pb-3 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="px-2 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle d-flex align-items-center justify-content-center fw-bold" 
                                             style="width: 42px; height: 42px; border-radius: 14px; background: {{ $customer->is_active ? '#f0fdf4' : '#f8fafc' }}; color: {{ $customer->is_active ? '#16a34a' : '#64748b' }}; font-size: 0.9rem;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">{{ $customer->name }}</div>
                                            <div class="text-slate-400 small d-flex align-items-center gap-1">
                                                <i class="bi bi-geo-alt" style="font-size: 0.75rem;"></i>
                                                {{ Str::limit($customer->address, 25) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-slate-700 fw-medium">{{ $customer->phone }}</div>
                                    @if($customer->email)
                                        <div class="text-slate-400 small">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill bg-primary-subtle text-primary fw-bold" style="font-size: 0.8rem; background-color: #f0f9ff;">
                                        {{ $customer->gallons_on_hand }} gal
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold {{ $customer->outstanding_balance > 0 ? 'text-danger' : 'text-success' }}">
                                        ₱{{ number_format($customer->outstanding_balance, 2) }}
                                    </div>
                                    <div class="text-{{ $customer->outstanding_balance > 0 ? 'danger' : 'success' }} opacity-75 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                        {{ $customer->outstanding_balance > 0 ? 'Outstanding' : 'Settled' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $customer->is_active ? 'bg-success text-white' : 'bg-slate-200 text-slate-500' }} px-3 py-2" style="font-size: 0.7rem; font-weight: 700;">
                                        {{ strtoupper($customer->is_active ? 'Active' : 'Inactive') }}
                                    </span>
                                </td>
                                <td class="text-end px-2">
                                    <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-white border-end px-3 py-2" style="background: white;">
                                            <i class="bi bi-eye text-slate-600"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-white px-3 py-2" style="background: white;">
                                            <i class="bi bi-pencil text-slate-600"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4 text-slate-200">
                    <i class="bi bi-people" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold text-slate-800">No customers found</h4>
                <p class="text-slate-500 mb-4">Try adjusting your search filters or add a new customer.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                    Add First Customer
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .bg-slate-200 { background-color: #e2e8f0; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .text-slate-500 { color: #64748b; }
    .text-slate-400 { color: #94a3b8; }
    .btn-white:hover { background-color: #f8fafc !important; }
    .bg-success-subtle { background-color: #f0fdf4; }
    .bg-primary-subtle { background-color: #f0f9ff; }
</style>
@endsection
