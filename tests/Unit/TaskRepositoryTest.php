<?php

namespace Tests\Unit;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TaskRepository $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = new TaskRepository(new Task());
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $createdUser = User::factory()->create();
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'status' => TaskStatusEnum::CREATED,
            'user_id' => $createdUser->id
        ];

        $createdTask = $this->taskRepository->create($taskData);

        $this->assertInstanceOf(Task::class, $createdTask);
        $this->assertEquals($taskData['title'], $createdTask->title);
        // Add assertions for other fields as well
    }

    /** @test */
    public function it_can_find_a_task_by_id()
    {
        $task = Task::factory()->create();

        $foundTask = $this->taskRepository->findById($task->id);

        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals($task->id, $foundTask->id);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create();
        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated task description.',
        ];

        $this->taskRepository->update($task->id, $updatedData);

        $updatedTask = Task::find($task->id);

        $this->assertEquals($updatedData['title'], $updatedTask->title);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $this->assertTrue($this->taskRepository->deleteById($task->id));
        $this->assertNull(Task::find($task->id));
    }

    /** @test */
    public function it_can_complete_a_task()
    {
        $task = Task::factory()->create();

        $this->assertTrue($this->taskRepository->makeTaskCompleted($task->id));
        $task = $this->taskRepository->findById($task->id);
        $this->assertEquals(TaskStatusEnum::COMPLETED->value, $task->status);
    }

    /** @test */
    public function it_can_make_tasks_complete_after_two_days()
    {
        $testCount = 10;
        Task::factory()->count($testCount)->create([
            'status' => TaskStatusEnum::CREATED,
            'created_at' => Carbon::now()->subDays(4),
            'completed_at' => null
        ]);


        $this->taskRepository->completeTasksAfterTwoDays();

        $completedTasksCount = Task::where('status', TaskStatusEnum::SYSTEM_COMPLETED->value)
            ->where('completed_at', '!=', null)->count();
        $this->assertEquals($testCount, $completedTasksCount);
    }
}
