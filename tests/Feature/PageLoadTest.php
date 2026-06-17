<?php

namespace Tests\Feature;

use App\Models\Fleet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class PageLoadTest extends TestCase
{
    use CreatesTestUsers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_landing_page_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_about_us_page_loads(): void
    {
        $this->get(route('about.us.index'))->assertOk();
    }

    public function test_vehicle_show_page_loads_for_active_fleet(): void
    {
        $provider = $this->createUserWithRole('FP');
        $fleet = $this->createFleetForProvider($provider, [
            'vehicle_name' => 'Public Show Vehicle',
            'status' => 'active',
        ]);

        $this->get(route('vehicle.show', $fleet->id))
            ->assertOk()
            ->assertSee('Public Show Vehicle')
            ->assertSee('Book now');
    }

    public function test_my_bookings_page_loads_for_customer(): void
    {
        $customer = $this->createApprovedCustomer();

        $this->actingAs($customer)
            ->get(route('customer.bookings.index'))
            ->assertOk();
    }

    public function test_admin_dashboard_loads_for_admin(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_fleet_provider_admin_pages_load(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $this->actingAs($admin)
            ->get(route('fleet-providers.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('fleet-providers.create'))
            ->assertOk();
    }

    public function test_user_verification_page_loads_for_customer(): void
    {
        $customer = $this->createUserWithRole('User');

        $this->actingAs($customer)
            ->get(route('user.validation'))
            ->assertOk();
    }

    public function test_verification_requests_page_loads_for_admin(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $this->actingAs($admin)
            ->get(route('verification_requests.index'))
            ->assertOk();
    }
}
