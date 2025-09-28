<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Regla mínima para shifts:
     * - schedule_id: FK a schedules
     * - weekday: 0..6 o nombre según convención
     * - start_time/end_time: formato HH:MM
     */
    public function rules(): array
    {
        return [
            'schedule_id' => ['required','integer','exists:schedules,id'],
            'weekday'     => ['required','integer','between:0,6'],
            'start_time'  => ['required','date_format:H:i'],
            'end_time'    => ['required','date_format:H:i','after:start_time'],
            'capacity'    => ['nullable','integer','min:1'],
            'notes'       => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'La hora de fin debe ser posterior a la de inicio.',
        ];
    }
}