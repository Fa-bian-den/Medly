<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleRequest extends FormRequest
{
    /**
     * Autorizar: cualquier usuario autenticado; la autorización fina se hace en Policy.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas para crear/actualizar un horario (schedule).
     * Ajusta atributos según tu migration (ej: user_id, center_id, timezone, active).
     */
    public function rules(): array
    {
        return [
            'user_id'    => ['required','integer','exists:users,id'],
            'center_id'  => ['nullable','integer','exists:centers,id'],
            'name'       => ['nullable','string','max:191'],
            'timezone'   => ['nullable','string','max:64'],
            'active'     => ['nullable','boolean'],
            'notes'      => ['nullable','string','max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Se requiere el usuario propietario del schedule.',
            'user_id.exists' => 'El usuario indicado no existe.',
        ];
    }
}