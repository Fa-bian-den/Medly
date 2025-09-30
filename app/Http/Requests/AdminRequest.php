<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios autenticados; políticas se aplican en el controlador
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;

        $adminModel = $httpRequest->route('admin');
        $adminId = $adminModel ? $adminModel->id : null;

        return [
            // Campos base (User)
            'first_name' => $adminId ? ['sometimes','string','max:150'] : ['required','string','max:150'],
            'last_name'  => $adminId ? ['sometimes','string','max:150'] : ['required','string','max:150'],
            'email'      => $adminId
                ? ['required','email','max:255', Rule::unique('users','email')->ignore($adminId)]
                : ['required','email','max:255','unique:users,email'],
            'password'   => $adminId ? ['sometimes','nullable','string','min:8'] : ['required','string','min:8'],
            'avatar'     => ['nullable','file','mimes:jpg,jpeg,png','max:5120'],

            // Metadatos/flags
            'status'     => ['sometimes','in:active,inactive,pending'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'El correo ya está siendo usado por otro usuario.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'avatar.mimes' => 'El avatar debe ser jpg o png.',
            'avatar.max'   => 'El avatar no debe superar 5 MB.',
        ];
    }
}