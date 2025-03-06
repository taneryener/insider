<?php

namespace Tests\Feature;

use App\Models\Team;
use Database\Seeders\TeamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication();
        $this->artisan('migrate');
    }

    /**
     * A basic feature test example.
     */
    public function test_returns_fixture(): void
    {
        $response = $this->get(route('fixture'));

        $response->assertStatus(200);
    }

    public function test_returns_new_fixture(): void
    {
        $this->seed(TeamSeeder::class);

        $teamCount = Team::all()->count();

        $response = $this->post(route('fixture.create'))
                         ->assertJsonCount($teamCount * ($teamCount -1),'data')
                         ->assertOk();

        $response->assertStatus(200);
    }
}
