<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
class Team extends Model
{
    use HasFactory;

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id');
    }
}
