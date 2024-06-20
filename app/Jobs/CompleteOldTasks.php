<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Events\TaskStatusChanged;

class CompleteOldTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $tasks = Task::where('created_at', '<', Carbon::now()->subDays(2))
            ->where('is_complete', false)
            ->get();

        foreach ($tasks as $task) {
            $task->update(['is_complete' => true]);
            event(new TaskStatusChanged($task));
        }
    }
}
