@extends('layouts.app')

@section('title', 'Products - AquaTrack')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Modern Header -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="fw-bold tracking-tight text-slate-800" style="font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Inventory
            </h1>
            <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 400;">
                Manage your station's <span class="text-primary fw-medium">products and supplies</span>
            </p>
        </div>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 12px; font-weight: 600;">
                <i class="bi bi-plus-lg"></i>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Modern Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-5 p-4" style="border-radius: 24px;">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-400"></i>
                    <input type="text" name="search" id="search-input" class="form-control border-0 bg-slate-50 ps-5 py-3" 
                           placeholder="Search by product name or category..." 
                           value="{{ request('search') }}"
                           style="border-radius: 16px; font-size: 1rem;">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="status" id="status-filter" class="form-select border-0 bg-slate-50 py-3" style="border-radius: 16px;">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock Alerts</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-slate-800 w-100 py-3 text-white" style="border-radius: 16px; background: #1e293b; font-weight: 600;">
                    Filter Inventory
                </button>
            </div>
        </form>
    </div>

    <!-- Modern Products Grid/Table -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        @if($products->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div class="text-slate-500 small fw-medium">
                    Showing <span class="text-slate-800 fw-bold">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> of <span class="text-slate-800 fw-bold">{{ $products->total() }}</span> products
                </div>
                <div class="d-flex gap-2">
                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2" style="font-size: 0.75rem; font-weight: 700;">
                        {{ $products->where('stock_quantity', '<=', 5)->count() }} LOW STOCK
                    </span>
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2" style="font-size: 0.75rem; font-weight: 700;">
                        {{ $products->where('is_active', true)->count() }} ACTIVE
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="border-0 px-2 pb-3">Product Info</th>
                            <th class="border-0 pb-3">Category</th>
                            <th class="border-0 pb-3">Price</th>
                            <th class="border-0 pb-3">Stock Level</th>
                            <th class="border-0 pb-3">Status</th>
                            <th class="border-0 text-end pb-3 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="px-2 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-icon d-flex align-items-center justify-content-center fw-bold" 
                                             style="width: 42px; height: 42px; border-radius: 14px; background: #f1f5f9; color: #475569;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">{{ $product->name }}</div>
                                            <div class="text-slate-400 small">{{ Str::limit($product->description, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-slate-600 fw-medium bg-slate-100 px-3 py-1 rounded-pill" style="font-size: 0.8rem;">
                                        {{ $product->category ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-slate-800">₱{{ number_format($product->price, 2) }}</div>
                                    <div class="text-slate-400 small">Cost: ₱{{ number_format($product->cost, 2) }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold {{ $product->stock_quantity <= $product->minimum_stock ? 'text-danger' : 'text-slate-700' }}">
                                            {{ $product->stock_quantity }} {{ $product->unit ?? 'pcs' }}
                                        </div>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px; width: 100px; border-radius: 2px;">
                                        @php
                                            $percent = $product->stock_quantity > 0 ? min(($product->stock_quantity / max($product->minimum_stock * 3, 1)) * 100, 100) : 0;
                                            $barColor = $product->stock_quantity <= $product->minimum_stock ? '#ef4444' : '#10b981';
                                        @endphp
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $product->is_active ? 'bg-success' : 'bg-slate-300' }} px-3 py-2" style="font-size: 0.7rem; font-weight: 700; color: white;">
                                        {{ strtoupper($product->is_active ? 'Active' : 'Archived') }}
                                    </span>
                                </td>
                                <td class="text-end px-2">
                                    <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-white border-end px-3 py-2" style="background: white;" title="Edit Product">
                                            <i class="bi bi-pencil text-slate-600"></i>
                                        </a>
                                        <a href="{{ route('products.stock', $product) }}" class="btn btn-white px-3 py-2" style="background: white;" title="Adjust Stock">
                                            <i class="bi bi-plus-slash-minus text-slate-600"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4 text-slate-200">
                    <i class="bi bi-box-seam" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold text-slate-800">No products found</h4>
                <p class="text-slate-500 mb-4">You haven't added any products to your inventory yet.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                    Add Your First Product
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .text-slate-500 { color: #64748b; }
    .text-slate-400 { color: #94a3b8; }
    .bg-success-subtle { background-color: #f0fdf4; }
    .bg-danger-subtle { background-color: #fef2f2; }
    .btn-white:hover { background-color: #f8fafc !important; }
</style>
@endsection
