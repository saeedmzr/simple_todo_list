<?php

namespace App\Http\Controllers;

use App\Repositories\BaseRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    private BaseRepository $userRepository;
    private BaseRepository $taskRepository;

    public function __construct(UserRepository $userRepository, TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    public function login(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function userTasksList(Request $request)
    {
        $userId = Auth::user()->id;
        $tasks = $this->taskRepository->owned($userId)->all();
        $pusherData = [
            "pusher_app_id" => env("PUSHER_APP_ID"),
            "pusher_key" => env("PUSHER_APP_KEY"),
            "pusher_secret" => env("PUSHER_APP_SECRET"),
            "pusher_cluster" => env("PUSHER_APP_CLUSTER"),
        ];
        return view("tasks", compact("tasks", "pusherData"));
    }

    public function userTasksShow(Request $request, int $taskId)
    {
        $userId = Auth::user()->id;
        $task = $this->taskRepository->owned($userId)->findById($taskId);
        $pusherData = [
            "pusher_app_id" => env("PUSHER_APP_ID"),
            "pusher_key" => env("PUSHER_APP_KEY"),
            "pusher_secret" => env("PUSHER_APP_SECRET"),
            "pusher_cluster" => env("PUSHER_APP_CLUSTER"),
        ];
        return view("task", compact("task", "pusherData"));
    }
}
