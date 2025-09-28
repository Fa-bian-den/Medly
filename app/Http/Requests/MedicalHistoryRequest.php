<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryRequest extends FormRequest
{
    /**
     * Autorizar la petición.
     * Permitimos a usuarios autenticados crear/editar; la autorización fina
     * debe residir en policies (MedicalHistoryPolicy).
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas de validación para crear/actualizar historiales médicos.
     * Ajusta o amplía según las columnas reales de la tabla medical_histories.
     *
     * - Respetar la convención user_id para FKs.
     * - Campos de contacto de emergencia están aquí porque los migramos a esta tabla.
     *
     * @return array
     */
    public function rules(): array
    {
        // Cuando es update es común pasar el id vía route-model-binding; no validamos unicidad aquí.
        return [
            'user_id' => ['nullable','integer','exists:users,id'],

            // Información clínica básica
            'blood_type' => ['nullable','string','in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'allergies' => ['nullable','string','max:1000'],
            'chronic_conditions' => ['nullable','string','max:1000'],
            'current_medications' => ['nullable','string','max:1000'],
            'notes' => ['nullable','string','max:2000'],

            // Contacto de emergencia (migrado a medical_histories)
            'emergency_contact_name' => ['nullable','string','max:150'],
            'emergency_contact_phone' => ['nullable','string','max:40'],

            // Datos demográficos opcionales (si decides mantenerlos aquí)
            'gender' => ['nullable','in:male,female,other,unknown'],
            'birthdate' => ['nullable','date'],

            // Archivos relacionados (si subes documentos desde este request)
            'documents' => ['nullable','array'],
            'documents.*' => ['file','mimes:pdf,jpg,jpeg,png','max:5120'], // max 5MB por archivo
        ];
    }

    /**
     * Mensajes personalizados (opcional pero útil).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'El usuario indicado no existe.',
            'blood_type.in' => 'Tipo de sangre inválido.',
            'documents.*.mimes' => 'Los documentos deben ser pdf, jpg o png.',
            'documents.*.max' => 'Cada documento no debe superar 5 MB.',
        ];
    }
}