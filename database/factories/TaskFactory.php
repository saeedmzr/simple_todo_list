<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the model associated with the factory.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->optional()->paragraph,
            'status' => TaskStatusEnum::random(),
            'deadline' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
            'completed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
