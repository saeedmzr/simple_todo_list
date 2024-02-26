<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CompleteTaskSchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="task's id"
 *     ),

 * )
 */

class CompleteTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => "required",
        ];
    }
}
