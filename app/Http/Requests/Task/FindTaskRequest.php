<?php

namespace App\Http\Requests\Task;

use App\Rules\TaskOwnerRule;
use Illuminate\Foundation\Http\FormRequest;

class FindTaskRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'task_id' => ['required', 'integer', new TaskOwnerRule($this->task_id)], // Assuming task_id is in the request
        ];
    }
}
