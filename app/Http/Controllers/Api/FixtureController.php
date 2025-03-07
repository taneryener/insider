<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FixtureResource;
use App\Models\Fixture;
use App\Repositories\FixtureRepository;
use App\Services\FixtureService;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FixtureController extends Controller
{
    private FixtureService $fixtureService;
    private TeamService $teamService;
    private FixtureRepository $fixtureRepository;

    public function __construct(FixtureService $fixtureService,FixtureRepository $fixtureRepository, TeamService $teamService)
    {
        $this->teamService       = $teamService;
        $this->fixtureService    = $fixtureService;
        $this->fixtureRepository = $fixtureRepository;
    }

    public function create(): JsonResource
    {
        $matches  = $this->fixtureService->create($this->teamService->all());
        $fixtures = $this->fixtureService->save($matches);

        return FixtureResource::collection($fixtures);
    }

    public function weeks(): JsonResource
    {
        return FixtureResource::collection(Fixture::all()->whereNull('result'));
    }

    public function fixture(): JsonResource
    {
        return FixtureResource::collection($this->fixtureService->fixture());
    }

    public function play(): JsonResource
    {
        $match       = $this->fixtureRepository->nextMatch();
        $matchResult = $this->fixtureService->playMatch($match);

        return new FixtureResource($matchResult);
    }

    public function playAll(): JsonResource
    {
        $matchResults   = [];
        $matches        = $this->fixtureRepository->matches();

        $matches->each(function ($match) use (&$matchResults) {
            $matchResults[] = $this->fixtureService->playMatch($match);
        });

        return FixtureResource::collection($matchResults);
    }

    public function delete(): JsonResponse
    {
        $this->fixtureRepository->deleteAll();

        return response()->json(['message'=>'fixture deleted']);
    }

    public function predictions(): JsonResponse
    {
        $prediction = $this->fixtureRepository->predictions();

        return response()->json($prediction);
    }

}
