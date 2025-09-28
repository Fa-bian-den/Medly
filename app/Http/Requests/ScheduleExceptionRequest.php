<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Regla para excepciones: schedule_id, date, start_time, end_time, reason, type (block/extra)
     */
    public function rules(): array
    {
        return [
            'schedule_id' => ['required','integer','exists:schedules,id'],
            'date'        => ['required','date'],
            'start_time'  => ['nullable','date_format:H:i'],
            'end_time'    => ['nullable','date_format:H:i','after:start_time'],
            'type'        => ['required','in:block,extra'],
            'reason'      => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Tipo inválido. Use block o extra.',
        ];
    }
}