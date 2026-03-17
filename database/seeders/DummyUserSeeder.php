<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dummy@aquatrack.com'],
            [
                'tenant_id' => 1,
                'name' => 'Dummy User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '09000000000',
                'is_active' => true,
            ]
        );
    }
}
