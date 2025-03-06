<?php

namespace App\Services;

use App\Repositories\FixtureRepository;
use Illuminate\Support\Collection;

class FixtureService
{
    private FixtureRepository $repository;

    /**
     * Create a new class instance.
     */
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
        $teamCount = $teams->count();
        $isOdd     = $teamCount % 2 !== 0;

        if ($isOdd) {
            $teams->push((object) ['id' => null, 'name' => 'Bay']); // Placeholder takım
            $teamCount++;
        }

        $weeks   = ($teamCount - 1) * 2;
        $fixture = collect();

        for ($week = 0; $week < $weeks; $week++) {
            $matches = collect();

            for ($i = 0; $i < $teamCount / 2; $i++) {
                $homeTeam = $i;
                $awayTeam = $teamCount - 1 - $i;

                if ($week >= $weeks / 2) {
                    $homeTeam = $teamCount - 1 - $i;
                    $awayTeam = $i;
                }

                $team1 = $teams[$homeTeam];
                $team2 = $teams[$awayTeam];

                if ($team1->id !== null && $team2->id !== null) {
                    $matches->push([
                        'home_team_id' => $team1->id,
                        'away_team_id' => $team2->id,
                        'week'         => $week
                    ]);
                }
            }

            $matches->flatMap(function ($match) use ($fixture) {
                $fixture->push($match);
            });

            $teams->splice(1, 0, [$teams->pop()]);
        }

        return $fixture;
    }
}
