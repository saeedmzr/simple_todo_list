<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->taskRepository = new TaskRepository(new Task());
    }

    /** @test */
    public function user_can_create_task()
    {
        $this->actingAs($this->user);

        $data = [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_see_tasks()
    {
        $this->actingAs($this->user);

        Task::factory(5)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function user_cant_see_others_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($this->user);

        Task::factory(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function user_can_update_task()
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description'
        ];
        $response = $this->putJson(route('tasks.update', $task->id), $updatedData);

        $response->assertStatus(201)
            ->assertJson(['data' => [
                'title' => 'Updated Task Title',
                'description' => 'Updated Task Description'
            ]]);

        $this->assertDatabaseHas('tasks', $updatedData);
    }

    /** @test */
    public function user_can_find_a_task()
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('tasks.show', $task->id));

        $response->assertOk()
            ->assertJson(['data' => [
                'id' => $task->id
            ]]);
    }

    /** @test */
    public function user_can_delete_task()
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('tasks.destroy', $task->id));

        $response->assertStatus(201);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_complete_a_task()
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create(['status' => TaskStatusEnum::CREATED, 'user_id' => $this->user->id]);
        $response = $this->postJson(route('tasks.completeTask'), ['id' => $task->id]);

        $response->assertOk();

        // Ensure task is marked as completed in the database
        $this->assertEquals(Task::find($task->id)->status, TaskStatusEnum::COMPLETED->value);
    }


}
