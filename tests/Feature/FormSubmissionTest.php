<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\FpDetail;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Models\UserValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use CreatesTestUsers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        Mail::fake();
    }

    public function test_booking_store_creates_booking_with_valid_dates(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        $response = $this->actingAs($customer)->postJson(route('bookings.store'), [
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-08-01',
            'to_date' => '2026-08-05',
            'total_price' => 20000,
            'payment_status' => 'pending',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-08-01',
            'to_date' => '2026-08-05',
        ]);

        $this->assertDatabaseHas('invoices', [
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
        ]);
    }

    public function test_booking_store_rejects_overlapping_dates(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-06-20',
            'to_date' => '2026-06-30',
            'total_price' => 50000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000001',
            'fee_amount' => 10000,
            'fp_amount' => 40000,
        ]);

        $response = $this->actingAs($customer)->postJson(route('bookings.store'), [
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-06-25',
            'to_date' => '2026-07-05',
            'total_price' => 30000,
            'payment_status' => 'pending',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_booking_store_allows_non_overlapping_dates_for_same_vehicle(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-06-20',
            'to_date' => '2026-06-30',
            'total_price' => 50000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000001',
            'fee_amount' => 10000,
            'fp_amount' => 40000,
        ]);

        $response = $this->actingAs($customer)->postJson(route('bookings.store'), [
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-10',
            'total_price' => 45000,
            'payment_status' => 'pending',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_booking_store_validates_required_fields(): void
    {
        $customer = $this->createApprovedCustomer();

        $response = $this->actingAs($customer)->postJson(route('bookings.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['fp_id', 'fleet_id', 'customer_id', 'from_date', 'to_date', 'total_price', 'payment_status']);
    }

    public function test_check_date_detects_conflicts_and_available_ranges(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-06-20',
            'to_date' => '2026-06-30',
            'total_price' => 50000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000002',
            'fee_amount' => 10000,
            'fp_amount' => 40000,
        ]);

        $this->actingAs($customer)->postJson(route('check.date'), [
            'id' => $fleet->id,
            'from_date' => '2026-06-25',
            'to_date' => '2026-07-05',
        ])->assertOk()
            ->assertJson(['available' => false]);

        $this->actingAs($customer)->postJson(route('check.date'), [
            'id' => $fleet->id,
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-10',
        ])->assertOk()
            ->assertJson(['available' => true]);
    }

    public function test_booking_cancel_succeeds_for_owner(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        $booking = Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-09-01',
            'to_date' => '2026-09-05',
            'total_price' => 25000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000003',
            'fee_amount' => 5000,
            'fp_amount' => 20000,
        ]);

        Invoice::create([
            'booking_id' => $booking->id,
            'booking_no' => $booking->booking_no,
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'due_date' => '2026-09-05',
        ]);

        $response = $this->actingAs($customer)->postJson(route('bookings.cancel', $booking->id));

        $response->assertOk()
            ->assertJson(['message' => 'Booking cancelled successfully.']);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'is_cancelled' => 1,
            'status' => 'cancelled',
        ]);
    }

    public function test_booking_cancel_rejects_other_customers(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $otherCustomer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        $booking = Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-09-01',
            'to_date' => '2026-09-05',
            'total_price' => 25000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000004',
            'fee_amount' => 5000,
            'fp_amount' => 20000,
        ]);

        $this->actingAs($otherCustomer)
            ->postJson(route('bookings.cancel', $booking->id))
            ->assertStatus(403);
    }

    public function test_checkout_payment_processes_valid_card_submission(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        $booking = Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-10-01',
            'to_date' => '2026-10-05',
            'total_price' => 25000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000005',
            'fee_amount' => 5000,
            'fp_amount' => 20000,
        ]);

        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'booking_no' => $booking->booking_no,
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'due_date' => '2026-10-05',
        ]);

        $response = $this->actingAs($customer)->post(route('checkout.process', $booking->id), [
            'card_holder_name' => 'John Doe',
            'card_number' => '4111 1111 1111 1111',
            'card_expiry' => '12/30',
            'card_cvv' => '123',
        ]);

        $response->assertRedirect(route('checkout.success', $booking->id));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('payment_history', [
            'booking_id' => $booking->id,
            'payer_name' => 'John Doe',
            'payment_method' => 'card',
        ]);
    }

    public function test_checkout_payment_rejects_invalid_card_data(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        $booking = Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-10-01',
            'to_date' => '2026-10-05',
            'total_price' => 25000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000006',
            'fee_amount' => 5000,
            'fp_amount' => 20000,
        ]);

        $response = $this->actingAs($customer)->post(route('checkout.process', $booking->id), [
            'card_holder_name' => 'John123',
            'card_number' => '1234',
            'card_expiry' => '13/99',
            'card_cvv' => '12',
        ]);

        $response->assertSessionHasErrors(['card_holder_name', 'card_number', 'card_expiry', 'card_cvv']);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'payment_status' => 'pending',
        ]);

        $this->assertDatabaseCount('payment_history', 0);
    }

    public function test_fleet_provider_store_creates_provider(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $response = $this->actingAs($admin)->post(route('fleet-providers.store'), [
            'name' => 'Fleet Provider',
            'email' => 'fp@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => '456 Provider Road',
        ]);

        $response->assertRedirect(route('fleet-providers.index'));

        $user = User::where('email', 'fp@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('FP'));
        $this->assertDatabaseHas('fp_detail', [
            'user_id' => $user->id,
            'address' => '456 Provider Road',
        ]);
    }

    public function test_fleet_provider_store_validates_input(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $response = $this->actingAs($admin)->post(route('fleet-providers.store'), [
            'name' => 'Bad Name 123',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
            'address' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'address']);
    }

    public function test_user_validation_store_creates_request(): void
    {
        $customer = $this->createUserWithRole('User');

        $response = $this->actingAs($customer)->post(route('user_validation.store'), [
            'identity_number' => '9876543210',
            'license_number' => 'DL-54321',
            'license_provider' => 'Traffic Police',
            'age' => 28,
            'address' => '789 Customer Street, Town',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('user_validation', [
            'user_id' => $customer->id,
            'identity_number' => '9876543210',
            'status' => 'pending',
        ]);
    }

    public function test_user_validation_store_rejects_invalid_data(): void
    {
        $customer = $this->createUserWithRole('User');

        $response = $this->actingAs($customer)->post(route('user_validation.store'), [
            'identity_number' => 'abc',
            'license_number' => 'x',
            'license_provider' => 'Bad123',
            'age' => 15,
            'address' => 'bad',
        ]);

        $response->assertSessionHasErrors([
            'identity_number',
            'license_number',
            'license_provider',
            'age',
            'address',
        ]);
    }

    public function test_verification_approve_marks_request_approved(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $customer = $this->createUserWithRole('User');

        $validation = UserValidation::create([
            'user_id' => $customer->id,
            'identity_number' => '1111111111',
            'license_number' => 'DL-11111',
            'license_provider' => 'Traffic Police',
            'age' => 30,
            'address' => 'Test Address Line',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->postJson(route('user_validation.approve'), [
            'id' => $validation->id,
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('user_validation', [
            'id' => $validation->id,
            'status' => 'approved',
        ]);
    }

    public function test_vehicle_show_page_loads_with_booked_ranges(): void
    {
        $provider = $this->createUserWithRole('FP');
        $customer = $this->createApprovedCustomer();
        $fleet = $this->createFleetForProvider($provider);

        Booking::create([
            'fp_id' => $provider->id,
            'fleet_id' => $fleet->id,
            'customer_id' => $customer->id,
            'from_date' => '2026-06-20',
            'to_date' => '2026-06-30',
            'total_price' => 50000,
            'payment_status' => 'pending',
            'booking_no' => 'RP000000007',
            'fee_amount' => 10000,
            'fp_amount' => 40000,
        ]);

        $this->actingAs($customer)
            ->get(route('vehicle.show', $fleet->id))
            ->assertOk()
            ->assertSee('Book now')
            ->assertSee('Some dates are already booked')
            ->assertSee('20 Jun 2026');
    }
}
