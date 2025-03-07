<?php

namespace App\Helpers;

use App\Models\Team;

class MatchHelper {
    public static function generateTotalGoals(int $homeTeamChance, int $awayTeamChance): int
    {
        $avgPower        = ($homeTeamChance + $awayTeamChance) / 2;
        $powerDifference = abs($homeTeamChance - $awayTeamChance);
        $baseGoals       = ceil($avgPower / 30);
        $randomFactor    = random_int(0, max(1, 5 - floor($powerDifference / 15)));

        return max(0, $baseGoals + $randomFactor);
    }

    public static function adjustForDraw(int $teamAPower, int $teamBPower, int &$teamAGoals, int &$teamBGoals): void
    {
        $powerDifference = abs($teamAPower - $teamBPower);
        $drawProbability = max(5, 30 - ($powerDifference / 2));

        if ($teamAGoals !== $teamBGoals && random_int(1, 100) <= $drawProbability) {
            $teamAGoals = $teamBGoals;
        }
    }

    public static function determineMatchResult(?int $teamAId, ?int $teamBId, int $teamAGoals, int $teamBGoals): ?int
    {
        return $teamAGoals > $teamBGoals ? $teamAId : ($teamBGoals > $teamAGoals ? $teamBId : null);
    }

    public static function weekCount(int $teamCount): int
    {
        return ($teamCount - 1) * 2;
    }
    public static function count(int $teamCount): int
    {
        return $teamCount * ($teamCount - 1);
    }

    public static function calculateTeamPower(Team $team, bool $isHome = true): int
    {
        return ($isHome ? $team->home_power : $team->away_power)
            + $team->supporter_strength
            + $team->goalkeeper_strength
            + $team->attacker_strength
            + $team->defence_strength;
    }
}
