<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentRequest extends FormRequest
{
    /**
     * Autorizar petición; la autorización fina se aplica en Policies.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas para crear/actualizar una cita.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required','integer','exists:users,id'],
            'user_id'    => ['required','integer','exists:users,id'], // médico responsable
            'center_id'  => ['nullable','integer','exists:centers,id'],
            'service_id' => ['nullable','integer','exists:services,id'],
            'slot_id'    => ['nullable','integer','exists:appointment_slots,id'],
            'scheduled_at' => ['required','date'],
            'status'     => ['nullable','in:pendiente,confirmado,reprogramado,atendido,cancelado,no se presento'],
            'reason'     => ['nullable','string','max:2000'],
            'created_by' => ['nullable','integer','exists:users,id'],
            'cancelled_by' => ['nullable','integer','exists:users,id'],
            'cancellation_reason' => ['nullable','string','max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Se requiere el paciente.',
            'user_id.required' => 'Se requiere el médico responsable.',
            'scheduled_at.required' => 'Se requiere fecha y hora programada.',
            'slot_id.exists' => 'El slot seleccionado no existe.',
        ];
    }
}