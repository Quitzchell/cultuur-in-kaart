<?php

namespace Tests;

use App\Models\Coordinator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        $this->artisan('migrate');

        $coordinator = Coordinator::factory()->create();

        $this->actingAs($coordinator);
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:rollback');

        parent::tearDown();
    }
}
