<?php

namespace Tests\Feature;

use App\Helpers\MatchHelper;
use App\Models\Team;
use App\Repositories\FixtureRepository;
use App\Services\FixtureService;
use Database\Seeders\TeamSeeder;
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
        $this->seed(TeamSeeder::class);

        $teamCount = Team::all()->count();

        $this->getJson(route('teams'))
             ->assertJsonCount($teamCount,'data') // checks team count
             ->assertOk();
    }

    public function test_returns_same_point_count_with_team(): void
    {
        $teamCount = Team::all()->count();

        $this->getJson(route('teams.points'))
             ->assertJsonCount($teamCount,'data') // checks team count
             ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'wins',
                        'losses',
                        'draws',
                        'goals',
                        'goal_difference',
                    ]
                ]
            ])
             ->assertOk();
    }

    public function test_checks_teams_points_by_match_results(): void
    {
        $this->prepareFixture();

        $fixtureService    = app(FixtureService::class);
        $fixtureRepository = app(FixtureRepository::class);
        $week              = $fixtureRepository->nextWeek();
        $matches           = $fixtureRepository->weekMatches($week);
        $matchResult       = $fixtureService->playAll($matches);

        $response = $this->getJson(route('teams.points'))
                         ->assertOk();

        $response->assertJsonFragment([
            'name'   => $matchResult->first()->homeTeam->name,
            'wins'   => $matchResult->first()->result == MatchHelper::HOME_WIN  ? 1 : 0,
            'draws'  => $matchResult->first()->result == MatchHelper::DRAW      ? 1 : 0,
            'losses' => $matchResult->first()->result == MatchHelper::HOME_LOSS ? 1 : 0,
        ]);

        $response->assertJsonFragment([
            'name'   => $matchResult->first()->awayTeam->name,
            'wins'   => $matchResult->first()->result == MatchHelper::AWAY_WIN  ? 1 : 0,
            'draws'  => $matchResult->first()->result == MatchHelper::DRAW      ? 1 : 0,
            'losses' => $matchResult->first()->result == MatchHelper::AWAY_LOSS ? 1 : 0,
        ]);
    }
}

