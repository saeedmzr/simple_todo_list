<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('get', [AuthController::class, 'get']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('tasks', TaskController::class);
    Route::group(['prefix' => 'tasks'], function () {
        Route::post('complete', [TaskController::class, 'completeTask']);
    });

});
