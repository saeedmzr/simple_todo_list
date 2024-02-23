<?php

namespace App\Rules;

use App\Models\Task;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TaskOwnerRule implements Rule
{
    private $taskId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    public function passes($attribute, $value)
    {
        $task = Task::find($this->taskId);
        return $task && $task->user_id === Auth::id();
    }

    public function message()
    {
        return 'You are not authorized to view this task.';
    }
}
