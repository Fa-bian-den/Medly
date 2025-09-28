<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentDocumentRequest extends FormRequest
{
    /**
     * Autorizar: cualquier usuario autenticado; la política hará la validación fina.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas para subir/actualizar documentos de cita.
     */
    public function rules(): array
    {
        return [
            'appointment_id' => ['required','integer','exists:appointments,id'],
            'type'           => ['nullable','string','max:100'],
            'document'       => ['required','file','mimes:pdf,jpg,jpeg,png','max:10240'], // max 10MB
            'metadata'       => ['nullable','json'],
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_id.required' => 'Se requiere la cita asociada.',
            'document.required' => 'Se requiere un archivo.',
            'document.mimes' => 'Formato de archivo inválido (pdf, jpg, png).',
            'document.max' => 'Archivo demasiado grande (máx 10 MB).',
        ];
    }
}