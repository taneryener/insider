<?php

namespace App\Repositories;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Collection;

class FixtureRepository
{
    public function fixture() : Collection
    {
        return Fixture::all();
    }
}
