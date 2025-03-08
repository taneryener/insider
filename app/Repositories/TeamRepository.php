<?php

namespace App\Repositories;

use App\Helpers\MatchHelper;
use App\Models\Team;
use Illuminate\Support\Collection;

class TeamRepository
{
    public function points(): Collection
    {
        $homeDrawResults = Team::join('fixture', 'teams.id', '=', 'fixture.home_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.home_score) as total_goals,
                         SUM(fixture.home_score - fixture.away_score) as goal_difference,
                         COUNT(fixture.id) as draw_count')
            ->where('fixture.result', MatchHelper::DRAW)
            ->groupBy('teams.id', 'teams.name')
            ->get();

        $homeWinResults = Team::join('fixture', 'teams.id', '=', 'fixture.home_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.home_score) as total_goals,
                         SUM(fixture.home_score - fixture.away_score) as goal_difference,
                         COUNT(fixture.id) as win_count')
            ->where('fixture.result', MatchHelper::HOME_WIN)
            ->groupBy('teams.id', 'teams.name')
            ->get();

        $homeLoseResults = Team::join('fixture', 'teams.id', '=', 'fixture.home_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.home_score) as total_goals,
                        SUM(fixture.home_score - fixture.away_score) as goal_difference,
                        COUNT(fixture.id) as loss_count')
            ->where('fixture.result', MatchHelper::HOME_LOSS)
            ->groupBy('teams.id', 'teams.name')
            ->get();

        $awayDrawResults = Team::join('fixture', 'teams.id', '=', 'fixture.away_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.away_score) as total_goals,
                         SUM(fixture.away_score - fixture.home_score) as goal_difference,
                         COUNT(fixture.id) as draw_count')
            ->where('fixture.result', MatchHelper::DRAW)
            ->groupBy('teams.id','teams.name')
            ->get();

        $awayWinResults = Team::join('fixture', 'teams.id', '=', 'fixture.away_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.away_score) as total_goals,
                         SUM(fixture.away_score - fixture.home_score) as goal_difference,
                         COUNT(fixture.id) as win_count')
            ->where('fixture.result', MatchHelper::AWAY_WIN)
            ->groupBy('teams.id','teams.name')
            ->get();

        $awayLoseResults = Team::join('fixture', 'teams.id', '=', 'fixture.away_team_id')
            ->select('teams.id', 'teams.name')
            ->selectRaw('SUM(fixture.away_score) as total_goals,
                         SUM(fixture.away_score - fixture.home_score) as goal_difference,
                         COUNT(fixture.id) as loss_count')
            ->where('fixture.result', MatchHelper::AWAY_LOSS)
            ->groupBy('teams.id','teams.name')
            ->get();

        $points = collect();

        return $points->merge($homeDrawResults)
                      ->merge($homeWinResults)
                      ->merge($homeLoseResults)
                      ->merge($awayDrawResults)
                      ->merge($awayWinResults)
                      ->merge($awayLoseResults);
    }
}
