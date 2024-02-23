<?php

namespace App\Observers;

use App\Enums\TaskStatusEnum;
use App\Jobs\AddCompletedAtToCompletedTaskJob;
use App\Models\Task;
use Carbon\Carbon;

class TaskObserver
{
    public function updated(Task $task): void
    {
        AddCompletedAtToCompletedTaskJob::dispatch($task);
    }
}
