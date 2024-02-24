<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->taskRepository = new TaskRepository(new Task());
    }
}
