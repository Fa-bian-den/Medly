<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentSlotRequest extends FormRequest
{
    /**
     * Autorizar: cualquier usuario autenticado; la política hace la validación fina.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas para crear/actualizar appointment slots.
     */
    public function rules(): array
    {
        return [
            'center_id'  => ['required','integer','exists:centers,id'],
            'user_id'    => ['required','integer','exists:users,id'], // médico responsable
            'service_id' => ['required','integer','exists:services,id'],
            'date'       => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time'   => ['required','date_format:H:i','after:start_time'],
            'capacity'   => ['nullable','integer','min:1','max:65535'],
        ];
    }

    /**
     * Mensajes personalizados mínimos.
     */
    public function messages(): array
    {
        return [
            'end_time.after' => 'La hora de fin debe ser posterior a la de inicio.',
            'start_time.date_format' => 'Formato de hora inválido. Use HH:MM.',
            'date.date' => 'Fecha inválida.',
        ];
    }
}