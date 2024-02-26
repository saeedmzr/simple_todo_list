<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('tasks.{taskId}', function (User $user, int $taskId) {
    return $user->id === Task::findOrNew($taskId)->user_id;
});

