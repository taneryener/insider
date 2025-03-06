<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    public function all() : Collection
    {
        return Team::all();
    }
}
