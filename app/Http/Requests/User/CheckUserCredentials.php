<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CheckUserCredentials extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'device_name' => 'required|string|max:255'
        ];
    }
}
