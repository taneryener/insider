<?php
namespace App\Services;

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
        return $this->teamRepository->points();
    }
}
