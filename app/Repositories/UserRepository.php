<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class UserRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login(string $email, string $password)
    {
        $user = $this->model->where('email', $email)->firstOrFail();

        if (!Hash::check($password, $user->password)) {
            return null;
        }
        $token = $user->createToken('auth');
        return $token->plainTextToken;
    }

    public function register(array $payload)
    {
        $user = $this->model->create($payload);
        $token = $user->createToken('auth');

        return $token->plainTextToken;
    }
}
