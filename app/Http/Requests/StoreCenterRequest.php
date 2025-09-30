<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;

class StoreCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Igual convención: permitir solo usuarios autenticados; policies se chequean en el controller
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;

        return [
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'name'            => ['required', 'string', 'max:255'],
            'logo'            => ['nullable', 'file', 'image', 'max:2048'],
            'address'         => ['nullable', 'string'],
            'url'             => ['nullable', 'url', 'max:1024'],
            'phone'           => ['nullable', 'string', 'max:50'],
            'ruc'             => ['nullable', 'string', 'max:50'],
            'is_public'       => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'municipality_id.required' => 'El municipio es obligatorio.',
            'municipality_id.exists'   => 'El municipio seleccionado no existe.',
            'name.required'            => 'El nombre del centro es obligatorio.',
            'logo.image'               => 'El logo debe ser una imagen válida.',
            'logo.max'                 => 'El logo no debe superar 2 MB.',
            'url.url'                  => 'La URL debe ser válida.',
        ];
    }
}