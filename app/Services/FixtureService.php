<?php

namespace App\Services;

use App\Helpers\MatchHelper;
use App\Models\Fixture;
use App\Repositories\FixtureRepository;
use Illuminate\Support\Collection;

class FixtureService
{
    private FixtureRepository $repository;
    private TeamService $teamService;

    public function __construct(FixtureRepository $fixtureRepository, TeamService $teamService)
    {
        $this->repository = $fixtureRepository;
        $this->teamService = $teamService;
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
            $teams->push((object) ['id' => null, 'name' => 'Bay']); // Placeholder team
            $teamCount++;
        }

        $weeks   = MatchHelper::weekCount($teamCount);
        $fixture = collect();

        $teamsArray = $teams->all();
        $fixedTeam  = array_shift($teamsArray);

        for ($week = 0; $week < $weeks; $week++) {
            $matches = collect();

            for ($i = 0; $i < $teamCount / 2; $i++) {
                $homeTeam = ($i == 0) ? $fixedTeam : $teamsArray[$i - 1];
                $awayTeam = $teamsArray[$teamCount - 2 - $i];

                if ($week % 2 == 1) {
                    [$homeTeam, $awayTeam] = [$awayTeam, $homeTeam];
                }

                if ($homeTeam->id !== null && $awayTeam->id !== null) {
                    $matches->push([
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'week'         => $week
                    ]);
                }
            }

            array_unshift($teamsArray, array_pop($teamsArray));

            $fixture = $fixture->merge($matches);
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
        $match->result     = ($winnerTeamId == $homeTeam->id) ? 1
                             : (($winnerTeamId == $awayTeam->id) ? 2 : 0);

        $match->save();

        return $match;
    }

    public function playAll(Collection $matches): Collection
    {
        $matchResults   = collect();
        $matches->each(function ($match) use (&$matchResults) {
            $matchResults->push($this->playMatch($match));
        });

        return $matchResults;
    }

    public function predictions() : Collection
    {
        $predictions            = collect();
        $totalWeekCount         = Fixture::distinct('week')->count();
        $unplayedMatchWeekCount = $this->repository->unplayedMatchWeeks()->count();

        if ($totalWeekCount == $unplayedMatchWeekCount || $unplayedMatchWeekCount == 0) {
            return collect();
        }

        $points                 = $this->teamService->points()
                                                    ->sortByDesc(function ($point) {
                                                        return $point['points'];
                                                    });

        $pointSum               = $points->sum('points') * $unplayedMatchWeekCount;
        $championPredictions    = $this->teamService->all();

        $championPredictions->each(function ($championPrediction) use ($unplayedMatchWeekCount, $points, $pointSum, $predictions) {
            $teamsPoint = $points->where('id', $championPrediction->id)->first()['points'] * $unplayedMatchWeekCount;
            $percentage = ($teamsPoint / $pointSum) * 100;

            $predictions->push([
                'id'         => $championPrediction->id,
                'name'       => $championPrediction->name,
                'percentage' => $percentage
            ]);
        });

        return $predictions->sortByDesc('percentage');
    }
}
