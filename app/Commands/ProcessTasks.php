<?php namespace App\Commands;

use App\Commands\Command;

use App\Models\Task;
use App\Services\TaskProcessor;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ProcessTasks extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $taskId;

	public function __construct(Task $task)
	{
		$this->taskId = $task->id;
	}
	public function handle(TaskProcessor $taskProcessor)
	{
		/** @var Task $task */
		$task = Task::find($this->taskId);
		$taskProcessor->process($task);
		$this->delete();
	}

}
