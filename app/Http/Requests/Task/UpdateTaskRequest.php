<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="UpdateTaskSchema",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="task's title"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="task's description"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="task's status"
 *     ),
 *     @OA\Property(
 *         property="deadline",
 *         type="datetime",
 *         description="task's deadline"
 *     ),

 * )
 */
class UpdateTaskRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ["required", "string"],
            'description' => ["nullable", "string"],
            'deadline' => ['nullable','date_format:Y-m-d H:i:s'],
            'status' => ['nullable', 'in:' . implode(',', TaskStatusEnum::all())],
        ];
    }
}
