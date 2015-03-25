<?php namespace App\Commands;

use App\Commands\Command;

use App\Models\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ProcessTasks extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * @param Task $task
	 */
	public function handle(Task $task)
	{
		$task->status = Task::STATUS_COMPLETED;
		$task->update();
	}

}
