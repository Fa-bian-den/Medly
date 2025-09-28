<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;

class DoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;

        $doctorModel = $httpRequest->route('doctor');
        $userId = $doctorModel ? $doctorModel->id : null;

        return [
            // Campos básicos en users
            'first_name' => ['required','string','max:150'],
            'last_name'  => ['required','string','max:150'],
            'email'      => ['required','email','max:255',"unique:users,email,{$userId}"],
            'avatar'     => ['nullable','file','mimes:jpg,jpeg,png','max:5120'],

            // Campos doctor_profiles
            'center_id_proposed' => ['nullable','integer','exists:centers,id'],
            'carnet_minsa'       => ['nullable','string','max:191'],
            'ruc'                => ['nullable','string','max:64'],
            'specialties'        => ['nullable','array'],
            'specialties.*'      => ['string','max:80'],

            // flujo de validación por admin
            'status_validation'  => ['nullable','in:pendiente,en_revision,aprobado,rechazado'],
            'reviewed_by'        => ['nullable','integer','exists:users,id'],
            'validated_at'       => ['nullable','date'],
            'comments'           => ['nullable','string','max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'El correo ya está siendo usado por otro usuario.',
            'avatar.mimes' => 'El avatar debe ser jpg o png.',
            'avatar.max' => 'El avatar no debe superar 5 MB.',
        ];
    }
}