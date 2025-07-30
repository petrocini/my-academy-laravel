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
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'sets' => 'required|array|min:1',
            'sets.*.exercise_id' => 'required|exists:exercises,id',
            'sets.*.weight' => 'nullable|numeric',
            'sets.*.repetitions' => 'nullable|integer',
            'sets.*.order' => 'nullable|integer|min:1',
        ];
    }
}
