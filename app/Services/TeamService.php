<?php
namespace App\Services;

use App\Helpers\MatchHelper;
use App\Models\Team;
use Illuminate\Support\Collection;

class TeamService
{
    public function all(): Collection
    {
        return Team::all();
    }

    public function playMatch(Team $teamA, Team $teamB): array
    {
        $teamAPower  = $this->calculateTeamPower($teamA, true); // Home team
        $teamBPower  = $this->calculateTeamPower($teamB, false); // Away team
        $totalPower  = $teamAPower + $teamBPower;
        $teamAChance = $teamAPower / $totalPower;
        $teamBChance = $teamBPower / $totalPower;
        $teamAGoals  = MatchHelper::generateTotalGoals($teamAChance, $teamBChance);
        $teamBGoals  = MatchHelper::generateTotalGoals($teamAChance, $teamBChance);

        MatchHelper::adjustForDraw($teamAPower, $teamBPower, $teamAGoals, $teamBGoals);

        $winnerId = MatchHelper::determineWinner($teamA->id, $teamB->id, $teamAGoals, $teamBGoals);

        return [
            'winner_id' => $winnerId,
            'teamA_goals' => $teamAGoals,
            'teamB_goals' => $teamBGoals
        ];
    }

    private function calculateTeamPower(Team $team, bool $isHome): int
    {
        return ($isHome ? $team->home_power : $team->away_power)
            + $team->supporter_strength
            + $team->goalkeeper_strength
            + $team->attacker_strength
            + $team->defence_strength;
    }

}
