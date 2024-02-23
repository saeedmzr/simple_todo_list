<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public mixed $password;
    public mixed $email;
    public mixed $name;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
