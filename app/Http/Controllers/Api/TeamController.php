<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\TeamService;

class TeamController extends Controller
{
    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function all(): JsonResource
    {
        return TeamResource::collection($this->teamService->teams());
    }
}
