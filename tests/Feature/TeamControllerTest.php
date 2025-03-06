<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication();
        $this->artisan('migrate');
    }

    public function test_returns_all_teams(): void
    {
        $teams = Team::factory()->count(32)->create();

        $response = $this->getJson(route('teams'))
                         ->assertJsonCount($teams->count(),'data')
                         ->assertOk();

        $response->assertStatus(200);
    }
}

