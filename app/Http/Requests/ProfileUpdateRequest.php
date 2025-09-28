<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;
        return $httpRequest->user() !== null;
    }

    public function rules(): array
    {
        return [
            // Solo permitir la edición de contacto rápido
            'phone' => ['nullable','string','max:30'],
            'emergency_contact_name' => ['nullable','string','max:150'],
            'emergency_contact_phone' => ['nullable','string','max:30'],
        ];
    }
}