<?php

namespace Tests\Feature;

use App\Models\FpDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AuthFormTest extends TestCase
{
    use CreatesTestUsers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_login_page_loads(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Login');
    }

    public function test_register_page_loads(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Register');
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->from(route('login'))->post(route('login'), []);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_rejects_invalid_email_format(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_rejects_short_password(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'short',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $this->createUserWithRole('User', [
            'email' => 'existing@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'existing@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_succeeds_for_customer_and_redirects_home(): void
    {
        $user = $this->createUserWithRole('User', [
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_succeeds_for_admin_and_redirects_to_dashboard(): void
    {
        $admin = $this->createUserWithRole('Admin', [
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_register_requires_valid_fields(): void
    {
        $response = $this->from(route('register'))->post(route('register'), []);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_register_rejects_invalid_name_email_and_short_password(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'John123',
            'email' => 'bad-email',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role' => 'User',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_register_rejects_password_mismatch(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
            'role' => 'User',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_requires_address_for_fleet_provider(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Fleet Provider',
            'email' => 'fp@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'FP',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['address']);
    }

    public function test_register_creates_customer_user(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'User',
        ]);

        $response->assertRedirect('/');

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('User'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_creates_fleet_provider_with_address(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Fleet Provider',
            'email' => 'newfp@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'FP',
            'address' => '123 Provider Street, Faisalabad',
        ]);

        $response->assertRedirect('/home');

        $user = User::where('email', 'newfp@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('FP'));
        $this->assertDatabaseHas('fp_detail', [
            'user_id' => $user->id,
            'address' => '123 Provider Street, Faisalabad',
        ]);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        $this->createUserWithRole('User', ['email' => 'taken@example.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Another User',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'User',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['email']);
    }
}
