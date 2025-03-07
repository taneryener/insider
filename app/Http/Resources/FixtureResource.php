<?php

namespace App\Http\Resources;

use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Fixture $resource
 */
class FixtureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'week'       => $this->resource->week + 1,
            'home_team'  => $this->resource->homeTeam(),
            'away_team'  => $this->resource->awayTeam(),
            'home_score' => $this->resource->home_score,
            'away_score' => $this->resource->away_score,
        ];
    }
}
