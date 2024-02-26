<?php

namespace App\Http\Controllers\Api;

use App\Events\UpdateTaskEvent;
use App\Http\Requests\Task\CompleteTaskRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(name="Task Management")
 */
class TaskController extends BaseController
{


    /**
     * @var TaskRepository
     */
    private TaskRepository $taskRepository;

    /**
     * TaskController constructor.
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get a paginated list of tasks",
     *                    tags={"Task Management"},
     *     description="Retrieves a list of tasks owned by the authenticated user. Use query parameters for filtering and pagination.",
     *     @OA\Parameter(
     *         name="filters",
     *         in="query",
     *         description="Optional filters for searching tasks (refer to your specific implementation)",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 enum={"completed", "create", "system_completed"},
     *                 description="Filter tasks by their status"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         description="Number of items per page (default 10)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/TaskSchema")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *      security={{"sanctumAuth": {}}}
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskRepository->owned($request->user()->id)->filtersAndPaginate($request->get('filters', []), $request->get('size', 10));

        return $this->successResponse([
            "items" => TaskResource::collection($tasks),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'total_pages' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'links' => $tasks->links(),
            ],
        ], "Task list has been fetched successfully.");
    }

    /**
     * @OA\Get(
     *     path="/tasks/{taskId}",
     *     summary="Get a task by ID",
     *     description="Retrieves a single task identified by its ID.",
     *     tags={"Task Management"},
     *     @OA\Parameter(
     *         name="taskId",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TaskSchema"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
 */
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

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     description="Creates a new task with the provided details.",
     *               tags={"Task Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTaskSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/TaskSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
 */
    public function store(CreateTaskRequest $request): JsonResponse
    {

        $task = $this->taskRepository->create($request->validated());

        return $this->successResponse(
            new TaskResource($task),
            "Task has been created successfully.",
            201
        );
    }

    /**
     * @OA\Put(
     *     path="/tasks/{taskId}",
     *     summary="Update a task",
     *     description="Updates an existing task with the provided details.",
     *               tags={"Task Management"},
     *     @OA\Parameter(
     *         name="taskId",
     *         in="path",
     *         description="ID of the task to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/TaskSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Could not update this task"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
 */
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

    /**
     * @OA\Post(
     *     path="/tasks/complete",
     *     summary="Complete a task",
     *     description="Marks a task as completed.",
     *               tags={"Task Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CompleteTaskSchema")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task completed successfully",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Task was already completed"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
 */

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

    /**
     * @OA\Delete(
     *     path="/tasks/{taskId}",
     *     summary="Delete a task",
     *     description="Deletes a task with the provided ID.",
     *               tags={"Task Management"},
     *     @OA\Parameter(
     *         name="taskId",
     *         in="path",
     *         description="ID of the task to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
 */
    public function destroy(Request $request, int $taskId): JsonResponse
    {
        $this->taskRepository->owned($request->user()->id)->deleteById($taskId);
        return $this->successResponse([], "Task has been deleted successfully.", 201);
    }
}
