<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/auth/login", function () {
    return view("login");
});

Route::post('/auth/login', [WebController::class, "login"])->name("login");

Route::group(["middleware"=>"auth","prefix"=>"tasks"],function(){
    Route::get('/', [WebController::class, "userTasksList"])->name("user.tasks");
    Route::get('/{taskId}', [WebController::class, "userTasksShow"])->name("user.tasks");

});


Route::get('/broadcasting/auth', function () {
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
