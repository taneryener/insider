<?php

namespace App\Services;

use App\Helpers\MatchHelper;
use App\Models\Fixture;
use App\Models\Team;
use App\Repositories\FixtureRepository;
use Illuminate\Support\Collection;

class FixtureService
{
    private FixtureRepository $repository;

    public function __construct(FixtureRepository $fixtureRepository)
    {
        $this->repository = $fixtureRepository;
    }

    public function fixture(): Collection
    {
        return $this->repository->fixture();
    }

    public function create(Collection $teams): Collection
    {
        $teams     = $teams->shuffle();
        $teamCount = $teams->count();
        $isOdd     = $teamCount % 2 !== 0;

        if ($isOdd) {
            $teams->push((object) ['id' => null, 'name' => 'Bay']); // Placeholder team
            $teamCount++;
        }

        $weeks   = ($teamCount - 1) * 2;
        $fixture = collect();

        for ($week = 0; $week < $weeks; $week++) {
            $matches = collect();

            for ($i = 0; $i < $teamCount / 2; $i++) {
                $homeTeamOrder = $i;
                $awayTeamOrder = $teamCount - 1 - $i;

                if ($week >= $weeks / 2) {
                    $homeTeamOrder = $teamCount - 1 - $i;
                    $awayTeamOrder = $i;
                }

                $homeTeam = $teams[$homeTeamOrder];
                $awayTeam = $teams[$awayTeamOrder];

                if ($homeTeam->id !== null && $awayTeam->id !== null) {
                    $matches->push([
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'week'         => $week
                    ]);
                }
            }

            $matches->flatMap(function ($match) use ($fixture) {
                $fixture->push($match);
            });
        }

        return $fixture;
    }

    public function save(Collection $matches): Collection
    {
        $matches->each(function ($match) {
           $this->repository->save($match);
        });

        return $this->repository->fixture();
    }

    public function playMatch(Fixture $match): Fixture
    {
        $homeTeam       = $match->homeTeam;
        $awayTeam       = $match->awayTeam;
        $homeTeamPower  = MatchHelper::calculateTeamPower($homeTeam);
        $awayTeamPower  = MatchHelper::calculateTeamPower($awayTeam, false);
        $totalPower     = $homeTeamPower + $awayTeamPower;
        $homeTeamChance = $homeTeamPower / $totalPower;
        $awayTeamChance = $awayTeamPower / $totalPower;
        $homeTeamGoals  = MatchHelper::generateTotalGoals($homeTeamChance, $awayTeamChance);
        $awayTeamGoals  = MatchHelper::generateTotalGoals($awayTeamChance, $homeTeamChance);

        MatchHelper::adjustForDraw($homeTeamPower, $awayTeamPower, $homeTeamGoals, $awayTeamGoals);

        $winnerTeamId = MatchHelper::determineMatchResult($homeTeam->id, $awayTeam->id, $homeTeamGoals, $awayTeamGoals);

        $match->home_score = $homeTeamGoals;
        $match->away_score = $awayTeamGoals;
        $match->result     = ($winnerTeamId == $homeTeam->id ? 1 : ($winnerTeamId == $awayTeam->id ? 2 : 0));

        $match->save();

        return $match;
    }
}
