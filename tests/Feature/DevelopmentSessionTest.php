<?php

namespace Tests\Feature;

use App\Support\DevelopmentSessionManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class DevelopmentSessionTest extends TestCase
{
    use CreatesTestUsers;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    protected function tearDown(): void
    {
        $tokenPath = storage_path('framework/dev-server.token');
        if (File::exists($tokenPath)) {
            File::delete($tokenPath);
        }

        parent::tearDown();
    }

    public function test_serve_reset_rotates_token_and_clears_database_sessions(): void
    {
        config(['session.driver' => 'database']);

        $user = $this->createUserWithRole('User', [
            'email' => 'session-user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post(route('login'), [
            'email' => 'session-user@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('sessions', 1);

        $firstToken = DevelopmentSessionManager::reset();

        $this->assertNotEmpty($firstToken);
        $this->assertDatabaseCount('sessions', 0);
        $this->assertSame($firstToken, DevelopmentSessionManager::currentServerToken());
    }

    public function test_middleware_logs_out_when_server_token_changes(): void
    {
        $this->app['env'] = 'local';

        $user = $this->createUserWithRole('User', [
            'email' => 'token-user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $token = DevelopmentSessionManager::rotateServerToken();

        $this->actingAs($user);
        $this->withSession(['_dev_server_token' => $token])
            ->get('/')
            ->assertOk();

        DevelopmentSessionManager::rotateServerToken();

        $this->withSession(['_dev_server_token' => $token])
            ->get('/')
            ->assertOk();

        $this->assertGuest();
    }
}
