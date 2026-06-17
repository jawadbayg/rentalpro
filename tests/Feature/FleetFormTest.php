<?php

namespace Tests\Feature;

use App\Models\Fleet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class FleetFormTest extends TestCase
{
    use CreatesTestUsers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_fleet_create_page_loads_for_fleet_provider(): void
    {
        $provider = $this->createUserWithRole('FP');

        $this->actingAs($provider)
            ->get(route('fleet.create'))
            ->assertOk()
            ->assertSee('Add New Vehicle')
            ->assertSee('Select Vehicle type')
            ->assertSee('Select fuel type');
    }

    public function test_fleet_create_page_loads_for_admin_with_provider_dropdown(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $provider = $this->createUserWithRole('FP', ['name' => 'Test FP Provider']);

        $this->actingAs($admin)
            ->get(route('fleet.create'))
            ->assertOk()
            ->assertSee('Select Fleet Provider')
            ->assertSee('Test FP Provider');
    }

    public function test_fleet_edit_page_loads_with_existing_vehicle(): void
    {
        $provider = $this->createUserWithRole('FP');
        $fleet = $this->createFleetForProvider($provider, [
            'license_plate' => 'EDIT1234',
            'vehicle_name' => 'Toyota Corolla',
        ]);

        $this->actingAs($provider)
            ->get(route('fleet.edit', $fleet->id))
            ->assertOk()
            ->assertSee('Edit Vehicle')
            ->assertSee('EDIT1234')
            ->assertSee('Toyota Corolla')
            ->assertSee('Select Vehicle type')
            ->assertSee('Select fuel type');
    }

    public function test_fleet_store_creates_vehicle_for_fleet_provider(): void
    {
        $provider = $this->createUserWithRole('FP');

        $response = $this->actingAs($provider)->post(route('fleet.store'), $this->validFleetFormData([
            'license_plate' => 'lhr 9876',
        ]));

        $response->assertRedirect(route('fleet.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('fleet', [
            'user_id' => $provider->id,
            'license_plate' => 'LHR9876',
            'vehicle_name' => 'Honda Civic',
            'vehicle_type' => 'Sedan',
            'fuel_type' => 'Petrol',
            'price_per_day' => 5000,
        ]);
    }

    public function test_fleet_store_creates_vehicle_for_admin_with_selected_provider(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $provider = $this->createUserWithRole('FP');

        $response = $this->actingAs($admin)->post(route('fleet.store'), $this->validFleetFormData([
            'user_id' => $provider->id,
            'license_plate' => 'ADM12345',
        ]));

        $response->assertRedirect(route('fleet.index'));

        $this->assertDatabaseHas('fleet', [
            'user_id' => $provider->id,
            'license_plate' => 'ADM12345',
        ]);
    }

    public function test_fleet_store_validates_required_fields(): void
    {
        $provider = $this->createUserWithRole('FP');

        $response = $this->actingAs($provider)->from(route('fleet.create'))->post(route('fleet.store'), []);

        $response->assertRedirect(route('fleet.create'));
        $response->assertSessionHasErrors([
            'vehicle_name',
            'vehicle_owner_name',
            'registration_date',
            'vehicle_type',
            'license_plate',
            'status',
            'charges_per_day',
        ]);
    }

    public function test_fleet_store_rejects_invalid_vehicle_and_fuel_type(): void
    {
        $provider = $this->createUserWithRole('FP');

        $response = $this->actingAs($provider)->from(route('fleet.create'))->post(route('fleet.store'), $this->validFleetFormData([
            'vehicle_type' => 'Truck',
            'fuel_type' => 'Hybrid',
        ]));

        $response->assertRedirect(route('fleet.create'));
        $response->assertSessionHasErrors(['vehicle_type', 'fuel_type']);
    }

    public function test_fleet_store_rejects_duplicate_license_plate(): void
    {
        $provider = $this->createUserWithRole('FP');
        $this->createFleetForProvider($provider, ['license_plate' => 'DUP12345']);

        $response = $this->actingAs($provider)->from(route('fleet.create'))->post(route('fleet.store'), $this->validFleetFormData([
            'license_plate' => 'dup 12345',
        ]));

        $response->assertRedirect(route('fleet.create'));
        $response->assertSessionHasErrors(['license_plate']);
    }

    public function test_fleet_update_saves_changes(): void
    {
        $provider = $this->createUserWithRole('FP');
        $fleet = $this->createFleetForProvider($provider, [
            'license_plate' => 'OLD12345',
            'vehicle_type' => 'Sedan',
            'fuel_type' => 'Petrol',
        ]);

        $response = $this->actingAs($provider)->put(route('fleet.update', $fleet->id), [
            'user_id' => $provider->id,
            'license_plate' => 'new 54321',
            'vehicle_name' => 'Updated SUV',
            'vehicle_owner_name' => 'Updated Owner',
            'registration_date' => '2023-05-10',
            'vehicle_type' => 'SUV',
            'fuel_type' => 'Diesel',
            'status' => 'active',
            'mileage' => 20,
        ]);

        $response->assertRedirect(route('fleet.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('fleet', [
            'id' => $fleet->id,
            'license_plate' => 'NEW54321',
            'vehicle_name' => 'Updated SUV',
            'vehicle_type' => 'SUV',
            'fuel_type' => 'Diesel',
        ]);
    }

    public function test_fleet_update_validates_required_fields(): void
    {
        $provider = $this->createUserWithRole('FP');
        $fleet = $this->createFleetForProvider($provider);

        $response = $this->actingAs($provider)->from(route('fleet.edit', $fleet->id))->put(route('fleet.update', $fleet->id), []);

        $response->assertRedirect(route('fleet.edit', $fleet->id));
        $response->assertSessionHasErrors([
            'vehicle_name',
            'vehicle_owner_name',
            'registration_date',
            'vehicle_type',
            'license_plate',
            'status',
        ]);
    }

    public function test_fleet_index_page_loads_for_provider(): void
    {
        $provider = $this->createUserWithRole('FP');
        $this->createFleetForProvider($provider, ['vehicle_name' => 'Listed Vehicle']);

        $this->actingAs($provider)
            ->get(route('fleet.index'))
            ->assertOk()
            ->assertSee('Listed Vehicle');
    }
}
