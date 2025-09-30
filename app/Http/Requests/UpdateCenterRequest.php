<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'municipality_id' => ['sometimes','exists:municipalities,id'],
            'name'            => ['sometimes','string','max:255'],
            'logo'            => ['nullable','file','image','max:2048'],
            'address'         => ['nullable','string'],
            'url'             => ['nullable','url','max:1024'],
            'phone'           => ['nullable','string','max:50'],
            'ruc'             => ['nullable','string','max:50'],
            'is_public'       => ['sometimes','boolean'],
        ];
    }
}