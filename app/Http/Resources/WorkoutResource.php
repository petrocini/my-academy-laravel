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
            'sets' => $this->sets->map(fn($set) => [
                'id' => $set->id,
                'exercise' => [
                    'id' => $set->exercise->id,
                    'name' => $set->exercise->name,
                    'category' => $set->exercise->category,
                ],
                'weight' => $set->weight,
                'repetitions' => $set->repetitions,
                'order' => $set->order
            ])
        ];
    }
}
