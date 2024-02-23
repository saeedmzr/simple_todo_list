<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->register($request->validated());
        return $this->successResponse(
            ['access_token' => $this],
            'You signed up successfully.'
        );
    }

    public function login(RegisterRequest $request): JsonResponse
    {
        $token = $this->userRepository->login($request->email, $request->password);
        if (!$token) return $this->errorResponse('Invalid credentials.', 422);
        return $this->successResponse(
            ['access_token' => $this],
            'You signed in successfully.'
        );
    }

    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->successResponse(new UserResource($user), 'You fetched your information Successfully.',);

    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if (empty($user)) {
            return $this->errorResponse(
                'Invalid or missing user information.'
                , 401);
        }
        $user->tokens()->delete();
        return $this->successResponse([], 'Successfully logged out.',);
    }
}
