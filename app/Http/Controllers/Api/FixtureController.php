<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FixtureResource;
use App\Services\FixtureService;
use App\Services\TeamService;
use Illuminate\Http\Resources\Json\JsonResource;

class FixtureController extends Controller
{
    private FixtureService $fixtureService;
    private TeamService $teamService;

    public function __construct(FixtureService $fixtureService, TeamService $teamService)
    {
        $this->fixtureService = $fixtureService;
        $this->teamService    = $teamService;
    }

    public function create() : JsonResource
    {
        $fixture = $this->fixtureService->create($this->teamService->all());

        return FixtureResource::collection($fixture);
    }

    public function fixture() : JsonResource
    {
        return FixtureResource::collection($this->fixtureService->fixture());
    }
}
