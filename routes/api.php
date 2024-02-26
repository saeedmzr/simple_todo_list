<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::get('get', [AuthController::class, 'get']);
        Route::post('logout', [AuthController::class, 'logout']);
    });


    Route::apiResource('tasks', TaskController::class);
    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
        Route::post('complete', [TaskController::class, 'completeTask'])->name('completeTask');
    });

});

//Broadcast authentication
Route::post('/broadcasting/auth', function () {
    // Check if user is authenticated
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Fetch the authenticated user
    $user = Auth::user();

    // Generate a temporary Pusher token
    $pusher = app('pusher');
    $token = $pusher->socketAuth($user->id, $user->name, strtotime('+1 hour'));

    return response()->json([
        'channel_data' => $token,
        'status' => 200,
    ]);
});
