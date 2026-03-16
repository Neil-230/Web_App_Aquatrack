@extends('layouts.app')

@section('title', 'Add Customer - AquaTrack')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">
                Add Customer
            </h1>
            <p style="color: #64748b; margin-top: 0.5rem;">
                Register a new customer for {{ $tenant->name }}.
            </p>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            ← Back to Customers
        </a>
    </div>

    <!-- Customer Form -->
    <div class="card">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; font-size: 1.125rem;">
                        📋 Customer Information
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name *</label>
                        <input type="text" name="name" id="name" class="form-input" 
                               value="{{ old('name', $prefilledName) }}" required>
                        @error('name')
                            <div style="color: var(--accent-color); font-size: 0.875rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" name="email" id="email" class="form-input" 
                               value="{{ old('email') }}" placeholder="customer@email.com">
                        @error('email')
                            <div style="color: var(--accent-color); font-size: 0.875rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" class="form-input" 
                               value="{{ old('phone') }}" placeholder="09XXXXXXXXX" required>
                        @error('phone')
                            <div style="color: var(--accent-color); font-size: 0.875rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="gallons_on_hand">Gallons on Hand</label>
                        <input type="number" name="gallons_on_hand" id="gallons_on_hand" 
                               class="form-input" value="{{ old('gallons_on_hand', 0) }}" min="0">
                        @error('gallons_on_hand')
                            <div style="color: var(--accent-color); font-size: 0.875rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; font-size: 1.125rem;">
                        📍 Address Information
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="address">Home Address *</label>
                        <textarea name="address" id="address" class="form-input" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div style="color: var(--accent-color); font-size: 0.875rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="delivery_address">Delivery Address</label>
                        <textarea name="delivery_address" id="delivery_address" class="form-input" 
                                  rows="3" placeholder="Leave empty if same as home address">{{ old('delivery_address') }}</textarea>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                            Different from home address? Specify here for delivery purposes.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-input" rows="3" 
                                  placeholder="Any special notes about this customer...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    ➕ Create Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
