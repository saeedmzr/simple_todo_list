<?php

namespace App\Observers;

use App\Enums\TaskStatusEnum;
use App\Jobs\AddCompletedAtToCompletedTaskJob;
use App\Models\Task;
use Carbon\Carbon;

class TaskObserver
{
    public function updating(Task $task): void
    {
        $original = $task->getOriginal();
        if ($task->isDirty('status') && $original['status'] == TaskStatusEnum::CREATED->value)
            AddCompletedAtToCompletedTaskJob::dispatch($task);
    }
}
