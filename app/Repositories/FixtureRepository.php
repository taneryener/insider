<?php

namespace App\Repositories;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $home_power
 * @property int $away_power
 * @property int $supporter_strength
 * @property int $goalkeeper_strength
 * @property int $attacker_strength
 * @property int $defence_strength
 */
class FixtureRepository
{
    public function fixture() : Collection
    {
        return Fixture::all();
    }

    public function save(array $fixtureData) : Fixture
    {
        $fixture = new Fixture();

        $fixture->home_team_id = $fixtureData['home_team_id'];
        $fixture->away_team_id = $fixtureData['away_team_id'];
        $fixture->week         = $fixtureData['week'];

        $fixture->save();

        return $fixture;
    }

    public function nextWeek() : int
    {
        return Fixture::select('week')
                      ->whereNull('result')
                      ->orderBy('week')
                      ->get()
                      ->firstOrFail()->week;
    }

    public function week(int $id): Fixture
    {
        return Fixture::where('week', $id)->get();
    }

    public function matches() : Collection
    {
        return Fixture::whereNull('home_score')
                      ->whereNull('away_score')
                      ->orderBy('week')
                      ->get();
    }

    public function deleteAll()
    {
        Fixture::all()->each(function (Fixture $fixture) {
            $fixture->delete();
        });
    }

    public function weekMatches(int $week) : Collection
    {
        return Fixture::where('week', $week)
                      ->whereNull('result')
                      ->get();
    }

    public function unplayedMatchWeeks(): Collection
    {
        return Fixture::select('week')
                      ->distinct('week')
                      ->whereNull('result')
                      ->get();
    }
}
