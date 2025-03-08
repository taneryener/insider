<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FixtureResource;
use App\Http\Resources\PredictionResource;
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
        $this->fixtureRepository->deleteAll(); //removes old fixuture data as softdelete

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

    public function nextMatches(): JsonResource
    {
        $matches        = $this->fixtureRepository->weekMatches($this->fixtureRepository->nextWeek());

        return FixtureResource::collection($matches);
    }

    public function playNextWeek(): JsonResource
    {
        $week        = $this->fixtureRepository->nextWeek();
        $matches     = $this->fixtureRepository->weekMatches($week);
        $matchResult = $this->fixtureService->playAll($matches);

        return FixtureResource::collection($matchResult);
    }

    public function playAll(): JsonResource
    {
        $matches        = $this->fixtureRepository->matches();
        $matchResults   = $this->fixtureService->playAll($matches);

        return FixtureResource::collection($matchResults);
    }

    public function delete(): JsonResponse
    {
        $this->fixtureRepository->deleteAll();

        return response()->json(['message'=>'fixture deleted']);
    }

    public function predictions(): JsonResource
    {
        $prediction = $this->fixtureService->predictions();

        if (!$prediction->count()) {
            response()->json([
                'message' => 'no matches found'
            ]);
        }

        return PredictionResource::collection($prediction);
    }
}
