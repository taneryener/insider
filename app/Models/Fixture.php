<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int  $home_team_id
 * @property int  $away_team_id
 * @property ?int $home_score
 * @property ?int $away_score
 * @property int  $week
 * @property ?int $result
 * @property Team $homeTeam
 * @property Team $awayTeam
 */
class Fixture extends Model
{
    protected $table = 'fixture';

    use HasFactory, softDeletes;

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
