<?php

namespace Tests\Feature;

use App\Helpers\MatchHelper;
use App\Models\Fixture;
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

    public function test_creates_new_fixture(): void
    {
        $this->seed(TeamSeeder::class);

        $teamCount = Team::all()->count();

        $this->post(route('fixture.create'))
             ->assertJsonCount(MatchHelper::count($teamCount), 'data') // match count should be
             ->assertOk();
    }

    public function test_returns_fixture(): void
    {
        $this->prepareFixture();

        $teamCount = Team::all()->count();
        $response  = $this->get(route('fixture'))
                          ->assertJsonCount(MatchHelper::count($teamCount), 'data')
                          ->assertOk();

        $response->assertStatus(200);
    }

    public function test_plays_all_matches(): void
    {
        $this->prepareFixture();

        $teamCount        = Team::all()->count();
        $availableMatches = Fixture::whereNull('result')->count();

        $this->postJson(route('fixture.play-all'))
             ->assertJsonCount(MatchHelper::count($teamCount), 'data')
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'week',
                         'home_team',
                         'away_team',
                         'home_score',
                         'away_score',
                     ],
                 ],
             ])
             ->assertOk();

        $this->assertEquals(0, Fixture::whereNull('result')->count());
        $this->assertGreaterThan(Fixture::whereNull('result')->count(), $availableMatches);
    }

    public function test_plays_next_match(): void
    {
        $this->prepareFixture();
        $availableMatchCount = Fixture::whereNull('result')->count();

        $this->postJson(route('fixture.play'))
             ->assertJson([
                'data' => [
                    'week' => 1,
                    'home_team' => [],
                    'away_team' => [],
                ],
             ])
             ->assertOk();

        $this->assertEquals($availableMatchCount ,Fixture::whereNull('result')->count() + 1);
    }

    public function test_removes_fixture(): void
    {
        $this->prepareFixture();

        $this->deleteJson(route('fixture.delete'))
             ->assertOk();

        $this->assertTrue(0 == Fixture::all()->count());
    }
}
