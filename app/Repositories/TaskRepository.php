<?php

namespace App\Repositories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TaskRepository extends BaseRepository
{
    protected Model $model;
    private $queryBuilder;

    public function __construct(Task $model)
    {
        $this->model = $model;
        $this->queryBuilder = $this->model->newQuery();
    }

    public function owned($userId = null)
    {
        $this->queryBuilder = $this->model::Owner($userId);
        return $this;
    }

    public function filtersAndPaginate(array $filters = [], int $size = 10)
    {
        return $this->queryBuilder->filters($filters)->paginate($size);
    }

    public function checkTaskWasCompleted($taskId)
    {
        try {
            $task = $this->findById($taskId);
            if ($task->status == TaskStatusEnum::COMPLETED || $task->status == TaskStatusEnum::SYSTEM_COMPLETED) {
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            Log::error("Error in fetching task :" . $taskId . "\nerror : " . $exception->getMessage());
        }
    }

    public function makeTaskCompleted($taskId): ?bool
    {
        try {
            DB::beginTransaction();
            $task = $this->findById($taskId);
            $task->status = TaskStatusEnum::COMPLETED;
            $task->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            Log::error("Error in completing task :" . $taskId . "\nerror : " . $exception->getMessage());
            DB::rollBack();
            return null;
        }
    }

    public function makeTaskCompletedBySystem($taskId)
    {
        try {
            DB::beginTransaction();
            $task = $this->findById($taskId);
            $task->status = TaskStatusEnum::SYSTEM_COMPLETED;
            $task->save();
            DB::commit();
        } catch (\Exception $exception) {
            Log::error("Error in completing task :" . $taskId . "\nerror : " . $exception->getMessage());
        }
    }

    public function completeTasksAfterTwoDays(): void
    {
        $this->model->where('created_at', '<', now()->subDays(2))
            ->where('status', '!=', TaskStatusEnum::COMPLETED)
            ->where('status', '!=', TaskStatusEnum::SYSTEM_COMPLETED)
            ->update(['status' => TaskStatusEnum::SYSTEM_COMPLETED]);
    }
}
