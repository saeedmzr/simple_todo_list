<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

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
