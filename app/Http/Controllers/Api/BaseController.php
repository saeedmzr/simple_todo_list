<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BaseController extends Controller
{
    /**
     * Returns a successful JSON response with the given data and status code.
     *
     * @param array|null $data The data to include in the response
     * @param int $statusCode The HTTP status code (default: 200)
     * @param array $headers Additional headers to include (optional)
     * @return JsonResponse
     */
    protected function successResponse(array|AnonymousResourceCollection|JsonResource  $data = null, string $message = "Success", int $statusCode = 200, array $headers = []): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $statusCode, $headers);
    }

    /**
     * Returns an error JSON response with the given message, status code, and other details.
     *
     * @param string $message The error message
     * @param int $statusCode The HTTP status code (default: 400)
     * @param array $errors Additional error details (optional)
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Returns a JSON response with validation errors from a ValidationException.
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function validationErrorResponse(ValidationException $exception): JsonResponse
    {
        return $this->errorResponse($exception->getMessage(), 422, $exception->errors());
    }
}
