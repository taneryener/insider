<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
