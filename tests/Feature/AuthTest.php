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

    /** @test */
    public function user_can_login()
    {
        $desiredPassword = "12345678";
        $createdUser = User::factory()->create([
            'password' => $desiredPassword
        ]);

        $data = [
            'email' => $createdUser->email,
            'password' => $desiredPassword,
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                ],
            ]);
    }

    /** @test */
    public function user_can_register()
    {
        $desiredPassword = "12345678";
        $desiredEmail = "test@test.com";


        $data = [
            'name' => "Test name",
            'email' => $desiredEmail,
            'password' => $desiredPassword,
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                ],
            ]);
        $this->assertDatabaseHas('users', [
            "email" => $desiredEmail
        ]);
    }

    /** @test */
    public function user_can_logout()
    {
        $desiredPassword = "12345678";

        $createdUser = User::factory()->create([
            'password' => $desiredPassword
        ]);

        $this->actingAs($createdUser);


        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(200);

    }

}
