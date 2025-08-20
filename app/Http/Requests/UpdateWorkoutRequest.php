<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exercise_id' => 'sometimes|required|exists:exercises,id',
            'date' => 'sometimes|required|date',
            'notes' => 'nullable|string',
            'sets' => 'sometimes|required|array|min:1',
            'sets.*.weight' => 'required|numeric|min:0.01',
            'sets.*.repetitions' => 'required|integer|min:1',
        ];
    }
}
