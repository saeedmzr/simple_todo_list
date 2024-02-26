<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="RegisterSchema",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="user's name"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="user's email that should be unique"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="user's password"
 *     ),
 * )
 */
class RegisterRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
