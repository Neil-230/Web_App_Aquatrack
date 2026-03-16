<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GallonContainer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->get('tenant');
        
        $customers = $tenant->customers()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create(Request $request)
    {
        $tenant = $request->get('tenant');
        $prefilledName = $request->get('name', '');
        return view('customers.create', compact('tenant', 'prefilledName'));
    }

    public function store(Request $request)
    {
        $tenant = $request->get('tenant');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,tenant_id,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'delivery_address' => 'nullable|string',
            'gallons_on_hand' => 'integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $customer = $tenant->customers()->create($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer created successfully!');
    }

    public function show(Request $request, Customer $customer)
    {
        $tenant = $request->get('tenant');
        
        // Ensure customer belongs to tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        $customer->load(['sales' => function ($query) {
            $query->latest()->take(10);
        }, 'gallonContainers' => function ($query) {
            $query->where('status', 'with_customer');
        }]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Request $request, Customer $customer)
    {
        $tenant = $request->get('tenant');
        
        // Ensure customer belongs to tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $tenant = $request->get('tenant');
        
        // Ensure customer belongs to tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id . ',id,tenant_id,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'delivery_address' => 'nullable|string',
            'outstanding_balance' => 'decimal:2|min:0',
            'gallons_on_hand' => 'integer|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(Request $request, Customer $customer)
    {
        $tenant = $request->get('tenant');
        
        // Ensure customer belongs to tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Check if customer has sales or containers
        if ($customer->sales()->count() > 0 || $customer->gallonContainers()->count() > 0) {
            return back()
                ->with('error', 'Cannot delete customer with existing sales or gallon containers.');
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    // Gallon container management
    public function depositContainer(Request $request, Customer $customer)
    {
        $tenant = $request->get('tenant');
        
        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'container_number' => 'required|string|unique:gallon_containers,container_number,NULL,id,tenant_id,' . $tenant->id,
            'deposit_amount' => 'required|decimal:2|min:0',
            'notes' => 'nullable|string',
        ]);

        $container = $tenant->gallonContainers()->create([
            'customer_id' => $customer->id,
            'container_number' => $validated['container_number'],
            'status' => 'with_customer',
            'deposit_amount' => $validated['deposit_amount'],
            'deposited_at' => now(),
            'notes' => $validated['notes'],
        ]);

        $customer->increment('gallons_on_hand');

        return back()
            ->with('success', 'Container deposited successfully!');
    }

    public function returnContainer(Request $request, Customer $customer, GallonContainer $container)
    {
        $tenant = $request->get('tenant');
        
        if ($customer->tenant_id !== $tenant->id || $container->tenant_id !== $tenant->id) {
            abort(403);
        }

        if ($container->customer_id !== $customer->id || $container->status !== 'with_customer') {
            return back()
                ->with('error', 'Invalid container return request.');
        }

        $container->update([
            'status' => 'at_station',
            'returned_at' => now(),
        ]);

        $customer->decrement('gallons_on_hand');

        return back()
            ->with('success', 'Container returned successfully!');
    }
}
