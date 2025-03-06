<?php
namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Collection;

class TeamService
{
    public function teams(): Collection
    {
        return Team::all();
    }
}
