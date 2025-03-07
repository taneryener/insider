<?php

namespace App\Repositories;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $home_power
 * @property int $away_power
 * @property int $supporter_strength
 * @property int $goalkeeper_strength
 * @property int $attacker_strength
 * @property int $defence_strength
 */
class FixtureRepository
{
    private TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function fixture() : Collection
    {
        return Fixture::all();
    }

    public function save(array $fixtureData) : Fixture
    {
        $fixture = new Fixture();

        $fixture->home_team_id = $fixtureData['home_team_id'];
        $fixture->away_team_id = $fixtureData['away_team_id'];
        $fixture->week         = $fixtureData['week'];

        $fixture->save();

        return $fixture;
    }

    public function nextMatch() : Fixture
    {
        return Fixture::whereNull('home_score')
                      ->whereNull('away_score')
                      ->orderBy('week')
                      ->get()
                      ->first();
    }
    public function matches() : Collection
    {
        return Fixture::whereNull('home_score')
                      ->whereNull('away_score')
                      ->orderBy('week')
                      ->get();
    }

    public function deleteAll()
    {
        Fixture::all()->each(function (Fixture $fixture) {
            $fixture->delete();
        });
    }

    public function predictions(): array
    {
        $standings = $this->teamRepository->points();

        $remainingFixtures = Fixture::whereNull('home_score')
                                    ->whereNull('away_score')
                                    ->get();

        foreach ($remainingFixtures as $fixture) {
            $homeTeam = $standings[$fixture->home_team_id];
            $awayTeam = $standings[$fixture->away_team_id];

            $homeWinProbability = ($homeTeam->wins * 3 + $homeTeam->draws) / max(1, $homeTeam->wins + $homeTeam->draws + $homeTeam->losses) * 100;
            $awayWinProbability = ($awayTeam->wins * 3 + $awayTeam->draws) / max(1, $awayTeam->wins + $awayTeam->draws + $awayTeam->losses) * 100;
            $drawProbability    = 100 - ($homeWinProbability + $awayWinProbability);

            $randomOutcome = rand(1, 100);

            if ($randomOutcome <= $homeWinProbability) {
                $standings[$fixture->home_team_id]->wins   += 1;
                $standings[$fixture->home_team_id]->points += 3;
                $standings[$fixture->away_team_id]->losses += 1;
            } elseif ($randomOutcome <= ($homeWinProbability + $drawProbability)) {
                $standings[$fixture->home_team_id]->draws  += 1;
                $standings[$fixture->home_team_id]->points += 1;
                $standings[$fixture->away_team_id]->draws  += 1;
                $standings[$fixture->away_team_id]->points += 1;
            } else {
                $standings[$fixture->away_team_id]->wins   += 1;
                $standings[$fixture->away_team_id]->points += 3;
                $standings[$fixture->home_team_id]->losses += 1;
            }
        }

        $sortedStandings = $standings->sortByDesc('points')->values();

        return [
            'predicted_winner' => $sortedStandings->first()->name,
            'final_standings' => $sortedStandings,
        ];
    }
}
