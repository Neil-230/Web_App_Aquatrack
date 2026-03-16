<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create subscription plans
        $basicPlan = SubscriptionPlan::create([
            'name' => 'Basic Plan',
            'slug' => 'basic',
            'price' => 299.00,
            'billing_cycle' => 'monthly',
            'max_customers' => 200,
            'max_transactions' => 1000,
            'features' => [
                'Support for up to 200 customers',
                'Basic sales and inventory tracking',
                'Delivery management',
                'Gallon container tracking'
            ],
            'is_active' => true,
        ]);

        $premiumPlan = SubscriptionPlan::create([
            'name' => 'Premium Plan',
            'slug' => 'premium',
            'price' => 599.00,
            'billing_cycle' => 'monthly',
            'max_customers' => null,
            'max_transactions' => null,
            'features' => [
                'Unlimited customers and transactions',
                'Advanced analytics and PDF monthly reports',
                'Delivery routes optimization',
                'Maps integration',
                'Priority support'
            ],
            'is_active' => true,
        ]);

        // Create sample tenants
        $tenant1 = Tenant::create([
            'name' => 'Johnnie Water Refilling Station',
            'slug' => 'johnnie-water',
            'address' => 'Lope Damasco St, Malaybalay City, Bukidnon',
            'phone' => '09123456789',
            'email' => 'info@johnniewater.com',
            'subscription_plan_id' => $basicPlan->id,
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'settings' => [
                'currency' => 'PHP',
                'timezone' => 'Asia/Manila',
                'gallon_deposit_fee' => 50.00,
            ],
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Water Sky Refilling Station',
            'slug' => 'water-sky',
            'address' => 'NHA Phase 3, Malaybalay City, Bukidnon',
            'phone' => '09987654321',
            'email' => 'contact@watersky.com',
            'subscription_plan_id' => $premiumPlan->id,
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'settings' => [
                'currency' => 'PHP',
                'timezone' => 'Asia/Manila',
                'gallon_deposit_fee' => 60.00,
            ],
        ]);

        // Create users for each tenant
        // Tenant 1 users
        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'John Admin',
            'email' => 'admin@johnniewater.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09123456789',
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Jane Staff',
            'email' => 'staff@johnniewater.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '09123456788',
            'is_active' => true,
        ]);

        // Tenant 2 users
        User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Sky Admin',
            'email' => 'admin@watersky.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09987654321',
            'is_active' => true,
        ]);

        // Create sample products for each tenant
        foreach ([$tenant1, $tenant2] as $tenant) {
            Product::create([
                'tenant_id' => $tenant->id,
                'name' => ' purified Drinking Water',
                'description' => '5-gallon container of purified drinking water',
                'price' => 35.00,
                'cost' => 15.00,
                'unit' => 'gallon',
                'stock_quantity' => 100,
                'minimum_stock' => 20,
                'category' => 'water',
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $tenant->id,
                'name' => 'Alkaline Water',
                'description' => '5-gallon container of alkaline water',
                'price' => 45.00,
                'cost' => 20.00,
                'unit' => 'gallon',
                'stock_quantity' => 50,
                'minimum_stock' => 10,
                'category' => 'water',
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $tenant->id,
                'name' => 'Mineral Water',
                'description' => '5-gallon container of mineral water',
                'price' => 40.00,
                'cost' => 18.00,
                'unit' => 'gallon',
                'stock_quantity' => 75,
                'minimum_stock' => 15,
                'category' => 'water',
                'is_active' => true,
            ]);
        }

        // Create sample customers
        Customer::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@email.com',
            'phone' => '09123456787',
            'address' => '123 Main St, Malaybalay City',
            'delivery_address' => '123 Main St, Malaybalay City',
            'outstanding_balance' => 0,
            'gallons_on_hand' => 2,
            'is_active' => true,
        ]);

        Customer::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Maria Santos',
            'email' => 'maria@email.com',
            'phone' => '09123456786',
            'address' => '456 Oak Ave, Malaybalay City',
            'delivery_address' => '456 Oak Ave, Malaybalay City',
            'outstanding_balance' => 70.00,
            'gallons_on_hand' => 1,
            'is_active' => true,
        ]);
    }
}
