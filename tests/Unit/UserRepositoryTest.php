<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new UserRepository(new User());
    }

    /** @test */
    public function test_login_method()
    {
        $desiredPassword = "12345678";
        $createdUser = User::factory()->create([
            'password' => $desiredPassword
        ]);
        $result = $this->userRepository->login($createdUser->email, $desiredPassword);

        $this->assertNotNull($result);
    }

    public function test_register_method()
    {

        $payload = [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "12345678",
        ];

        $result = $this->userRepository->register($payload);

        $this->assertNotNull($result);
    }


    /** @test */
    public function it_can_find_a_user_by_id()
    {
        $user = User::factory()->create();

        $foundUser = $this->userRepository->findById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }


}
