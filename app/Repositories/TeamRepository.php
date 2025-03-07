<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    public function all() : Collection
    {
        return Team::all();
    }

    public function points() : Collection
    {
        return Team::select('teams.id', 'teams.name')
                    ->withCount([
                        // Wins
                        'homeMatches as home_wins' => fn($q) => $q->where('result', 1),
                        'awayMatches as away_wins' => fn($q) => $q->where('result', 2),

                        // Draws
                        'homeMatches as home_draws' => fn($q) => $q->where('result', 0),
                        'awayMatches as away_draws' => fn($q) => $q->where('result', 0),

                        // Losses
                        'homeMatches as home_losses' => fn($q) => $q->where('result', 2),
                        'awayMatches as away_losses' => fn($q) => $q->where('result', 1),

                        // Goals Scored
                        'homeMatches as home_goals_for' => fn($q) => $q->selectRaw('COALESCE(SUM(home_score), 0)'),
                        'awayMatches as away_goals_for' => fn($q) => $q->selectRaw('COALESCE(SUM(away_score), 0)'),

                        // Goals Conceded
                        'homeMatches as home_goals_against' => fn($q) => $q->selectRaw('COALESCE(SUM(away_score), 0)'),
                        'awayMatches as away_goals_against' => fn($q) => $q->selectRaw('COALESCE(SUM(home_score), 0)'),
                    ])
                    ->get()
                    ->map(function ($team) {
                        $team->wins            = $team->home_wins + $team->away_wins;
                        $team->draws           = $team->home_draws + $team->away_draws;
                        $team->losses          = $team->home_losses + $team->away_losses;
                        $team->goals_for       = $team->home_goals_for + $team->away_goals_for;
                        $team->goals_against   = $team->home_goals_against + $team->away_goals_against;
                        $team->goal_difference = $team->goals_for - $team->goals_against;
                        $team->points          = ($team->wins * 3) + ($team->draws * 1);

                        return $team;
                    })
                    ->sortByDesc('points')
                    ->values();
    }
}
