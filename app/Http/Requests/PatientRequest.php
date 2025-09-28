<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this; // anotar al IDE que esto implementa Illuminate\Http\Request

        $patientModel = $httpRequest->route('patient');
        $userId = $patientModel ? $patientModel->id : null;

        return [
            // Campos de users
            'first_name' => ['required','string','max:150'],
            'last_name'  => ['required','string','max:150'],
            'email'      => ['required','email','max:255',"unique:users,email,{$userId}"],
            'avatar'     => ['nullable','file','mimes:jpg,jpeg,png','max:5120'],

            // Campos de profiles
            'birthdate' => ['nullable','date'],
            'address'   => ['nullable','string','max:2000'],
            'idcard'    => ['nullable','string','max:200'],
            'phone'     => ['nullable','string','max:40'],
            'gender'    => ['nullable','in:male,female,other'],

            // metadata / professional
            'documents_metadata' => ['nullable','json'],
            'professional_details' => ['nullable','json'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'El correo ya está siendo usado por otro usuario.',
            'avatar.mimes' => 'El avatar debe ser jpg o png.',
            'avatar.max' => 'El avatar no debe superar 5 MB.',
            'gender.in' => 'Género inválido.',
        ];
    }
}