<?php namespace App\Http\Controllers;

use App\Models\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SiteMapController extends Controller {

	/**
	 * @return TaskRepository
	 */
	private function getTaskRepository()
	{
		return $this->taskRepository;
	}

	/**
	 * @param TaskRepository $taskRepository
	 */
	public function __construct(TaskRepository $taskRepository)
	{
		$this->taskRepository = $taskRepository;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('site-map.index');
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\View\View
	 */
	public function submit(Request $request)
	{
		$this->validate($request, [
			'url' => 'required|url',
		]);
		$baseURL = $request->get('url');
		$task = $this->getTaskRepository()->getOrCreate($baseURL);

		View::share('task', $task);
		return view('site-map.index');
	}

}
