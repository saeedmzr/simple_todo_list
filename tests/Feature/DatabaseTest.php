<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->taskRepository = new TaskRepository(new Task());
    }

    /** @test */
    public function database_up_and_running()
    {
        $createdUser = User::factory()->create();
        $createdTask = Task::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $createdUser->email
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $createdTask->id,
            'title' => $createdTask->title,
            'description' => $createdTask->description,
        ]);
    }
}
