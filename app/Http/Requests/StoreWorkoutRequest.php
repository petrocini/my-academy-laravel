<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
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
            'sets.*.weight' => 'nullable|numeric|min:0.01',
            'sets.*.repetitions' => 'nullable|integer|min:0.01',
            'sets.*.order' => 'nullable|integer|min:1'
        ];
    }
}
