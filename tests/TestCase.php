<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->assertUsesIsolatedTestDatabase();
    }

    protected function assertUsesIsolatedTestDatabase(): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if ($connection !== 'sqlite' || $database !== ':memory:') {
            $this->fail(
                'Tests must run against sqlite :memory: only. Your real database is never used. '.
                "Current: {$connection} / {$database}"
            );
        }
    }
}
