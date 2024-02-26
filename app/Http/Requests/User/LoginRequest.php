<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="LoginSchema",

 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="user's email"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="user's password"
 *     ),
 * )
 */
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
