<?php

namespace App\Helpers;

class MatchHelper {
    public static function generateTotalGoals(int $teamAChance, int $teamBChance): int
    {
        $avgPower        = ($teamAChance + $teamBChance) / 2;
        $powerDifference = abs($teamAChance - $teamBChance);
        $baseGoals       = ceil($avgPower / 30);
        $randomFactor    = random_int(0, max(2, 7 - floor($powerDifference / 15)));

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

}
