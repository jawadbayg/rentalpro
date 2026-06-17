<?php

namespace Tests\Concerns;

use App\Models\Fleet;
use App\Models\User;
use App\Models\UserValidation;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait CreatesTestUsers
{
    protected function seedRoles(): void
    {
        foreach (['Admin', 'User', 'FP'] as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }
    }

    protected function createUserWithRole(string $role, array $overrides = []): User
    {
        $user = User::create(array_merge([
            'name' => ucfirst($role).' User',
            'email' => strtolower($role).'_'.uniqid().'@example.com',
            'password' => Hash::make('password123'),
        ], $overrides));

        $user->assignRole($role);

        return $user;
    }

    protected function createApprovedCustomer(): User
    {
        $customer = $this->createUserWithRole('User');

        UserValidation::create([
            'user_id' => $customer->id,
            'identity_number' => '1234567890',
            'license_number' => 'DL-12345',
            'license_provider' => 'Traffic Police',
            'age' => 25,
            'address' => '123 Main Street, City',
            'status' => 'approved',
        ]);

        return $customer;
    }

    protected function createFleetForProvider(User $provider, array $overrides = []): Fleet
    {
        return Fleet::create(array_merge([
            'user_id' => $provider->id,
            'vehicle_no' => null,
            'vehicle_name' => 'Test Sedan',
            'vehicle_owner_name' => 'Owner Name',
            'registration_date' => '2024-01-01',
            'vehicle_type' => 'Sedan',
            'license_plate' => 'ABC'.random_int(1000, 9999),
            'manufacturing_year' => null,
            'status' => 'active',
            'price_per_day' => 5000,
            'fuel_type' => 'Petrol',
        ], $overrides));
    }

    protected function validFleetFormData(array $overrides = []): array
    {
        return array_merge([
            'license_plate' => 'LHR'.random_int(1000, 9999),
            'vehicle_name' => 'Honda Civic',
            'vehicle_type' => 'Sedan',
            'fuel_type' => 'Petrol',
            'vehicle_owner_name' => 'John Doe',
            'registration_date' => '2024-01-01',
            'status' => 'active',
            'charges_per_day' => 5000,
            'mileage' => 15,
            'no_of_seats' => 5,
            'no_of_doors' => 4,
            'no_of_bags' => 2,
            'color' => 'White',
        ], $overrides);
    }
}
