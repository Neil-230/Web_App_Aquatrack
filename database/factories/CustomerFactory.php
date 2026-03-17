<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'delivery_address' => $this->faker->address(),
            'outstanding_balance' => $this->faker->randomFloat(2, 0, 500),
            'gallons_on_hand' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
            'notes' => $this->faker->sentence(),
        ];
    }
}
