<?php
/**
 * Description of TaskRepository.php
 * @copyright Copyright (c) THREE POINT PRODUCTIONS, LLC
 * @author    Egor Gerasimchuk <egerasimchuk@dev.football.com>
 */

namespace App\Models\Repositories;


use App\Commands\ProcessTasks;
use App\Models\Task;
use Illuminate\Foundation\Bus\DispatchesCommands;

class TaskRepository
{

    use DispatchesCommands;

    private function generateKey($url)
    {
        return md5($url);
    }

    /**
     * @param $url
     * @param array $data
     * @return Task
     */
    public function getOrCreate($url, $data = [])
    {
        $key = $this->generateKey($url);
        $data['key'] = $key;
        $data['url'] = $url;
        $task = Task::where('key', $key)->first();
        if (!$task) {
            $task = Task::create($data);
            $this->dispatch(new ProcessTasks($task));
        }

        return $task;
    }

} 