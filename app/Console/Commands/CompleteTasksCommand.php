<?php

namespace App\Console\Commands;

use App\Repositories\TaskRepository;
use Illuminate\Console\Command;

class CompleteTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todolist:completeTasksAfterTwoDays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Complete Tasks after 2 days.';

    /**
     * Execute the console command.
     */
    public function handle(TaskRepository $taskRepository): void
    {
        $taskRepository->completeTasksAfterTwoDays();
    }
}
