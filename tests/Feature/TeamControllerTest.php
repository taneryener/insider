<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        $this->app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    }

    public function test_returns_all_teamsx(): void
    {
        dump(route('teams')); // 🔍 This will show the actual route Laravel is using

        $response = $this->get('/api/teams');
        $response->assertJsonCount(3, 'data')->assertOk();
    }

    public function test_returns_all_teams(): void
    {
        Team::factory()->count(3)->create();

        $response = $this->get('/api/teams');

        $response->assertJsonCount(3, 'data')
                 ->assertOk();

    }
}
