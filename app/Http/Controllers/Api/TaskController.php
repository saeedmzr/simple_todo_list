<?php

namespace App\Http\Controllers\Api;

use App\Events\UpdateTaskEvent;
use App\Http\Requests\Task\CompleteTaskRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\BaseRepository;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends BaseController
{
    private BaseRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request): JsonResponse
    {

        $tasks = $this->taskRepository->owned($request->user()->id)->filtersAndPaginate($request->get('filters', []), $request->get('size', 10));
        return $this->successResponse(
            TaskResource::collection($tasks),
            "Task list has been fetched successfully."
        );
    }

    public function show(Request $request, int $taskId): JsonResponse
    {

        $task = $this->taskRepository->owned($request->user()->id)->findById($taskId);
        if ($task)
            return $this->successResponse(
                new TaskResource($task),
                "Task has been fetched successfully."
            );
        return $this->errorResponse("Task Not Found.", 404);

    }

    public function store(CreateTaskRequest $request): JsonResponse
    {

        $task = $this->taskRepository->create($request->validated());

        return $this->successResponse(
            new TaskResource($task),
            "Task has been created successfully.",
            201
        );
    }

    public function update(UpdateTaskRequest $request, int $taskId): JsonResponse
    {
        try {
            $this->taskRepository->owned($request->user()->id)->update($taskId, $request->validated());
            $task = $this->taskRepository->owned($request->user()->id)->findById($taskId);
            event(new UpdateTaskEvent($task));

            return $this->successResponse(
                new TaskResource($task),
                "Task has been updated successfully.",
                201
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse("Could not update this task.", 403);
        }

    }


    public function completeTask(CompleteTaskRequest $request): JsonResponse
    {
        $taskId = $request->id;
        $result = $this->taskRepository->owned($request->user()->id)->checkTaskWasCompleted($taskId);
        if ($result)
            return $this->errorResponse("Task was Already completed.", 403);
        $this->taskRepository->makeTaskCompleted($taskId);
        $task = $this->taskRepository->owned($request->user()->id)->findById($taskId);
        event(new UpdateTaskEvent($task));
        return $this->successResponse([], "Task has been completed successfully.");
    }

    public function destroy(Request $request, int $taskId): JsonResponse
    {
        $this->taskRepository->owned($request->user()->id)->deleteById($taskId);
        return $this->successResponse([], "Task has been deleted successfully.", 201);
    }
}
