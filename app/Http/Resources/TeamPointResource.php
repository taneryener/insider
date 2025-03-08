<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->resource['id'],
            'name'            => $this->resource['name'],
            'wins'            => $this->resource['wins'],
            'losses'          => $this->resource['losses'] ?? 0,
            'draws'           => $this->resource['draws']  ?? 0,
            'goals'           => $this->resource['goals']  ?? 0,
            'goal_difference' => $this->resource['goal_difference'] ?? 0,
            'points'          => $this->resource['points'] ?? 0,
        ];
    }
}
