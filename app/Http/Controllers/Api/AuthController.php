<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Authentication")
 */
class AuthController extends BaseController
{

    private BaseRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="User registration",
     *     description="Registers a new user with the provided details.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterSchema")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->userRepository->register($request->validated());
        return $this->successResponse(
            ['access_token' => $token],
            'You signed up successfully.'
        );
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="User login",
     *     description="Logs in a user with the provided credentials.",
     *       tags={"Authentication"},
     *        @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginSchema")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials"
     *     )
     * )
     */

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->userRepository->login($request->email, $request->password);
        if (!$token) return $this->errorResponse('Invalid credentials.', 422);
        return $this->successResponse(
            ['access_token' => $token],
            'You signed in successfully.'
        );
    }

    /**
     * @OA\Get(
     *     path="/auth/get",
     *     summary="Get user information",
     *          tags={"Authentication"},
     *     description="Retrieves information of the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserSchema")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function get(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->successResponse(new UserResource($user), 'You fetched your information Successfully.',);

    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="User logout",
     *          tags={"Authentication"},
     *     description="Logs out the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/broadcast/token",
     *     summary="Generate a temporary Pusher token for broadcasting",
     *     description="Generates a temporary Pusher token for the authenticated user to use in broadcasting operations.",
     *     @OA\Response(
     *         response=200,
     *         description="Token generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="channel_data", type="string"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Invalid or missing user information"
     *     )
     * )
     */
    public function broadcastToken(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user or empty($user)) {
            return $this->errorResponse(
                'Invalid or missing user information.'
                , 401);
        }

        // Generate a temporary Pusher token
        $pusher = app('pusher');
        $token = $pusher->socketAuth($user->id, $user->name, strtotime('+1 hour'));

        return $this->successResponse([
            'channel_data' => $token,
        ], 'Successfully fetched token for broadcasting.');

    }
}
