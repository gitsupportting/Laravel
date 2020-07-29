<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TestCase extends \Tests\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\TestDatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->withoutExceptionHandling();
    }
}
