<?php

namespace Tests;
use App\Models\Team;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshApplication();
    }

    protected function prepareFixture() : void
    {
        $fixtureService = app(FixtureService::class);
        $teams          = Team::factory()->count(4)->create();
        $matches        = $fixtureService->create($teams);

        $fixtureService->save($matches);
    }
}
