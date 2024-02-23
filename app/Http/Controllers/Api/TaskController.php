<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Task\CompleteTaskRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request): JsonResponse
    {

        $tasks = $this->taskRepository->owned()->list($request->filters, $request->size);
        return $this->successResponse(
            TaskResource::collection($tasks),
            "Task list has been fetched successfully."
        );
    }

    public function find(int $taskId): JsonResponse
    {

        $task = $this->taskRepository->owned()->findById($taskId);
        if ($task)
            return $this->successResponse(
                new TaskResource($task),
                "Task list has been fetched successfully."
            );
        return $this->errorResponse("Task Not Found.", 404);

    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        $task = $this->taskRepository->create($request->validated());
        return $this->successResponse(
            new TaskResource($task),
            "Task has been created successfully.",
            201
        );
    }


    public function completeTask(CompleteTaskRequest $request): JsonResponse
    {
        $result = $this->taskRepository->owned()->checkTaskWasCompleted($request->task_id);
        if ($result)
            return $this->errorResponse("Task was Already completed.", 403);
        $this->taskRepository->makeTaskCompleted($request->task_id);
        return $this->successResponse([], "Task has been completed successfully.");
    }
}
