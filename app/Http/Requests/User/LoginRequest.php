<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
