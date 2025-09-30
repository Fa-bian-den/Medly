<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as BaseRequest;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Requerir usuario autenticado; policies se aplican en controller
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var BaseRequest $httpRequest */
        $httpRequest = $this;

        // Detectar si es update (marca lectura) por route-model binding {notification}
        $notificationModel = $httpRequest->route('notification');
        $isUpdate = (bool) $notificationModel;

        return [
            'user_id' => $isUpdate ? ['sometimes','exists:users,id'] : ['required','exists:users,id'],
            'type'    => ['nullable','string','max:191'],
            'title'   => $isUpdate ? ['sometimes','string','max:191'] : ['required','string','max:191'],
            'body'    => ['nullable','string'],
            'data'    => ['nullable'], // aceptamos array/object/json string; normalizamos en prepareForValidation
            'read_at' => ['sometimes','nullable','date'],
            'sent_at' => ['sometimes','nullable','date'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El destinatario es obligatorio.',
            'user_id.exists' => 'El usuario destinatario no existe.',
            'title.required' => 'El título es obligatorio.',
        ];
    }
}