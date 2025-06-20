<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the coordinator implements Authenticatable
        $coordinator = \App\Models\Coordinator::factory()->create();
        $this->actingAs($coordinator instanceof Authenticatable ? $coordinator : null);
    }
}
