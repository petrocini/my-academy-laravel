<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'notes' => $this->notes,
            'exercise' => [
                'id' => $this->exercise->id ?? null,
                'name' => $this->exercise->name ?? null,
                'category' => $this->exercise->category ?? null,
            ],
            'sets' => $this->sets->map(fn($set) => [
                'id' => $set->id,
                'weight' => $set->weight,
                'repetitions' => $set->repetitions
            ])
        ];
    }
}
