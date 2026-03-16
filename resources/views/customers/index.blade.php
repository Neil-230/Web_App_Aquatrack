@extends('layouts.app')

@section('title', 'Customers - AquaTrack')

@section('content')
<div class="container">
    <!-- Enhanced Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                <i class="bi bi-people me-2"></i>Customers
            </h1>
            <p style="color: var(--text-secondary); font-size: 1rem;">
                Manage your water refilling station customer relationships.
            </p>
        </div>
        @if(!request('search'))
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                <span>Add Customer</span>
            </a>
        @else
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Clear Search</span>
            </a>
        @endif
    </div>

    <!-- Enhanced Search and Filters -->
    <div class="card">
        <form method="GET" action="{{ route('customers.index') }}" id="search-form" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px; position: relative;">
                <label class="form-label"><i class="bi bi-search me-1"></i>Search Customers</label>
                <input type="text" name="search" id="search-input" class="form-input" 
                       placeholder="Search by name, email, or phone..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
                <div id="search-suggestions" style="position: absolute; background: white; border: 1px solid var(--border-color); border-radius: var(--radius); box-shadow: var(--shadow); z-index: 1000; max-height: 200px; overflow-y: auto; display: none; margin-top: 0.25rem;">
                </div>
            </div>
            
            <div style="min-width: 150px;">
                <label class="form-label"><i class="bi bi-bar-chart me-1"></i>Status</label>
                <select name="status" id="status-filter" class="form-select">
                    <option value="">All Customers</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                <span>Search</span>
            </button>
            
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Clear</span>
            </a>
        </form>
        
        @if(request('search') || request('status'))
            <div style="margin-top: 1rem; padding: 0.75rem; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: var(--radius); border-left: 4px solid var(--primary-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">
                        @if(request('search'))
                            <i class="bi bi-search me-1"></i>Searching for: <strong>"{{ request('search') }}"</strong>
                        @endif
                        @if(request('status'))
                            <span class="ms-2"><i class="bi bi-bar-chart me-1"></i>Status: <strong>{{ ucfirst(request('status')) }}</strong></span>
                        @endif
                    </div>
                    <a href="{{ route('customers.index') }}" style="font-size: 0.875rem; color: var(--primary-color); text-decoration: none; font-weight: 500;">
                        <i class="bi bi-x-circle me-1"></i>Clear filters
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Customers Table -->
    <div class="card">
        @if($customers->count() > 0)
            <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">
                        {{ $customers->where('is_active', true)->count() }} Active
                    </span>
                    <span style="background: var(--text-muted); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">
                        {{ $customers->where('is_active', false)->count() }} Inactive
                    </span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person-badge me-1"></i>Customer</th>
                            <th><i class="bi bi-telephone me-1"></i>Contact</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Address</th>
                            <th><i class="bi bi-droplet me-1"></i>Gallons</th>
                            <th><i class="bi bi-cash-coin me-1"></i>Balance</th>
                            <th><i class="bi bi-bar-chart me-1"></i>Status</th>
                            <th><i class="bi bi-lightning-charge me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="customer-name" style="font-weight: 600; color: var(--text-primary);">{{ $customer->name }}</div>
                                            @if($customer->email)
                                                <div style="font-size: 0.875rem; color: var(--text-secondary);"><i class="bi bi-envelope me-1"></i>{{ $customer->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="bi bi-phone"></i>
                                            <span style="font-weight: 500;">{{ $customer->phone }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 200px;">
                                        <div style="font-size: 0.875rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" 
                                             title="{{ $customer->address }}">
                                            <i class="bi bi-house-door me-1"></i>{{ $customer->address }}
                                        </div>
                                        @if($customer->delivery_address && $customer->delivery_address !== $customer->address)
                                            <div style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.25rem;" 
                                                 title="{{ $customer->delivery_address }}">
                                                <i class="bi bi-truck me-1"></i>{{ $customer->delivery_address }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <span style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i class="bi bi-droplet"></i>
                                            <span>{{ $customer->gallons_on_hand }} gal</span>
                                        </span>
                                        @if($customer->gallons_on_hand > 0)
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                On hand
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <div style="font-weight: 600; color: {{ $customer->outstanding_balance > 0 ? 'var(--accent-color)' : 'var(--success-color)' }};">
                                            {{ $customer->formatted_balance }}
                                        </div>
                                        @if($customer->outstanding_balance > 0)
                                            <div style="font-size: 0.75rem; color: var(--accent-color);">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Outstanding
                                            </div>
                                        @else
                                            <div style="font-size: 0.75rem; color: var(--success-color);">
                                                <i class="bi bi-check-circle me-1"></i>Settled
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span style="background: {{ $customer->is_active ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #6b7280, #4b5563)' }}; color: white; padding: 0.375rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="bi {{ $customer->is_active ? 'bi-check-circle' : 'bi-pause-circle' }}"></i>
                                        <span>{{ $customer->is_active ? 'Active' : 'Inactive' }}</span>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                           class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;"
                                           data-tooltip="View customer details">
                                            <i class="bi bi-eye"></i>
                                            <span>View</span>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;"
                                           data-tooltip="Edit customer information">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div style="margin-top: 2rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                {{ $customers->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.5;">
                    <i class="bi {{ request('search') ? 'bi-search' : 'bi-people' }}"></i>
                </div>
                <h3 style="color: var(--text-secondary); margin-bottom: 0.75rem; font-size: 1.5rem;">
                    {{ request('search') ? 'No customers found for "' . e(request('search')) . '"' : 'No customers yet' }}
                </h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    {{ request('search') 
                        ? 'No customers match your search criteria. Try different keywords or check the spelling. You can also add a new customer if this is a new contact.' 
                        : 'Get started by adding your first customer to begin tracking their water refilling orders and gallon containers.' 
                    }}
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    @if(request('search'))
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Clear Search</span>
                        </a>
                        <a href="{{ route('customers.create') }}?name={{ urlencode(request('search')) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            <span>Add "{{ e(request('search')) }}" as New Customer</span>
                        </a>
                    @else
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            <span>Add Your First Customer</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Enhanced Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const statusFilter = document.getElementById('status-filter');
    const searchSuggestions = document.getElementById('search-suggestions');
    let searchTimeout;
    let isSearching = false;

    // Auto-search on typing with debounce
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const searchTerm = e.target.value.trim();
        
        if (searchTerm.length >= 2 && !isSearching) {
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, 800); // Increased debounce to 800ms for better UX
        } else if (searchTerm.length === 0) {
            // Clear search if input is empty
            const url = new URL(window.location);
            url.searchParams.delete('search');
            url.searchParams.delete('page'); // Also reset page
            window.location.href = url.toString();
        }
    });

    // Auto-filter on status change
    statusFilter.addEventListener('change', function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set('status', this.value);
        } else {
            url.searchParams.delete('status');
        }
        url.searchParams.delete('page'); // Reset page when filtering
        window.location.href = url.toString();
    });

    // Handle Enter key in search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = e.target.value.trim();
            if (searchTerm.length >= 2) {
                performSearch(searchTerm);
            }
        }
    });

    // Perform real-time search with focus preservation
    function performSearch(searchTerm) {
        if (isSearching) return;
        isSearching = true;
        
        // Add loading state
        searchInput.classList.add('search-loading');
        
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('search', searchTerm);
        currentUrl.searchParams.delete('page'); // Reset to first page
        
        // Store cursor position
        const cursorPos = searchInput.selectionStart;
        const scrollPos = window.scrollY;
        
        fetch(currentUrl.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response and update the table
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update the customers table
            const newTable = doc.querySelector('.card tbody');
            const currentTable = document.querySelector('.card tbody');
            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
            }
            
            // Update pagination
            const newPagination = doc.querySelector('.card div[style*="justify-content: center"]');
            const currentPagination = document.querySelector('.card div[style*="justify-content: center"]');
            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            }
            
            // Update result count
            const newCount = doc.querySelector('.card div[style*="color: var(--text-secondary)"]');
            const currentCount = document.querySelector('.card div[style*="color: var(--text-secondary)"]');
            if (newCount && currentCount) {
                currentCount.textContent = newCount.textContent;
            }
            
            // Update URL without page reload
            window.history.pushState({}, '', currentUrl.toString());
            
            // Restore focus and cursor position
            setTimeout(() => {
                searchInput.focus();
                searchInput.setSelectionRange(cursorPos, cursorPos);
                window.scrollTo(0, scrollPos);
                highlightSearchTerms();
            }, 100);
        })
        .catch(error => {
            console.error('Search failed:', error);
            // Fallback to form submission
            searchForm.submit();
        })
        .finally(() => {
            isSearching = false;
            searchInput.classList.remove('search-loading');
        });
    }

    // Highlight search terms in results
    function highlightSearchTerms() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length >= 2) {
            const customerNames = document.querySelectorAll('.customer-name');
            customerNames.forEach(element => {
                const text = element.textContent;
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                element.innerHTML = text.replace(regex, '<mark style="background: #fef3c7; padding: 2px; border-radius: 2px;">$1</mark>');
            });
        }
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+K or Cmd+K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            const url = new URL(window.location);
            url.searchParams.delete('search');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    });

    // Maintain focus on page load if there's a search term
    if (searchInput.value.trim()) {
        setTimeout(() => {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }, 100);
    }

    // Add search hint
    const searchHint = document.createElement('div');
    searchHint.style.cssText = `
        position: absolute;
        top: -20px;
        right: 0;
        font-size: 0.75rem;
        color: var(--text-muted);
    `;
    searchHint.textContent = 'Press Ctrl+K to search';
    searchInput.parentElement.style.position = 'relative';
    searchInput.parentElement.appendChild(searchHint);

    // Hide hint when focused
    searchInput.addEventListener('focus', function() {
        searchHint.style.display = 'none';
    });

    searchInput.addEventListener('blur', function() {
        setTimeout(() => {
            searchHint.style.display = 'block';
        }, 200);
    });

    // Initialize highlights
    highlightSearchTerms();
});

// Add CSS for search enhancements
const searchStyles = document.createElement('style');
searchStyles.textContent = `
    mark {
        background: #fef3c7;
        color: #92400e;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 600;
    }
    
    .customer-name {
        transition: all 0.2s ease;
    }
    
    #search-input:focus {
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .search-loading {
        position: relative;
    }
    
    .search-loading::after {
        content: '';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #ddd;
        border-top: 2px solid var(--primary-style);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
    
    /* Prevent page scroll during search */
    .search-container {
        position: relative;
    }
`;
document.head.appendChild(searchStyles);
</script>
@endsection
