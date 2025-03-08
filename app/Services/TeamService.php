<?php
namespace App\Services;

use App\Helpers\MatchHelper;
use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Support\Collection;

class TeamService
{
    private TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }
    public function all(): Collection
    {
        return Team::all();
    }

    public function points(): Collection
    {
        $points = $this->teamRepository->points();
        $teams  = $this->all();

        return $teams->map(function ($team) use ($points) {
            $wins           = $points->where('id', $team->id)->sum('win_count');
            $losses         = $points->where('id', $team->id)->sum('loss_count');
            $draws          = $points->where('id', $team->id)->sum('draw_count');
            $goalDifference = $points->where('id', $team->id)->sum('goal_difference');

            return [
                'id'              => $team->id,
                'name'            => $team->name,
                'wins'            => $wins,
                'losses'          => $losses,
                'draws'           => $draws,
                'total_goals'     => $points->where('id', $team->id)->sum('total_goals'),
                'goal_difference' => $goalDifference,
                'points'          => MatchHelper::calculateTeamPoint($draws, $wins, $losses),
            ];
        });
    }
}
