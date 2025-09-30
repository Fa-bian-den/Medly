<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Igual que en otros Requests: permitir solo usuarios autenticados
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;

        // Detectar si es update a través del route-model binding {service}
        $serviceModel = $httpRequest->route('service');
        $serviceId = $serviceModel ? $serviceModel->id : null;

        return [
            // nombre: obligatorio en create, opcional en update
            'name' => $serviceId ? ['sometimes', 'string', 'max:255'] : ['required', 'string', 'max:255'],

            // código único opcional: aplicar unique ignorando el id en update
            'code' => $serviceId
                ? ['nullable', 'string', 'max:100', Rule::unique('services', 'code')->ignore($serviceId)]
                : ['nullable', 'string', 'max:100', Rule::unique('services', 'code')],

            // descripción: opcional; en update usamos sometimes para no sobrescribir con null
            'description' => $serviceId ? ['sometimes', 'nullable', 'string'] : ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'El código ya está en uso por otro servicio.',
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.max' => 'El nombre no debe superar 255 caracteres.',
        ];
    }
}