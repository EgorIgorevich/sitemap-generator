<?php namespace App\Http\Controllers;

use App\Commands\ProcessTasks;
use Illuminate\Http\Request;
use App\Models\Task;
use Roumen\Sitemap\Sitemap;

class SiteMapController extends Controller {

	/**
	 *
	 */
	public function __construct()
	{
//		$this->middleware('guest');
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
		$task = Task::fromUrl($baseURL);
		$this->dispatch(
			new ProcessTasks($task)
		);
		return view('site-map.index');
//		$html = @file_get_contents($baseURL);
//
//		$urls = $this->retrieveAllURLs($html);
//		$urls = $this->filterInternalUrls($urls, $baseURL);
//
//		$this->generateSiteMap($urls, $baseURL);
	}

	private function generateSiteMap($urls, $baseURL)
	{
		$baseURL = rtrim($baseURL, '/');
		/** @var Sitemap $sitemap */
		$sitemap = \App::make("sitemap");
		foreach ($urls as $url) {
			$sitemap->add("{$baseURL}{$url}", date('Y-m-d'), 1, 'daily');
		}
		$sitemap->store('xml', "assets/sitemaps/".md5($baseURL));
		echo md5($baseURL), '.xml';
	}

	private function retrieveAllURLs($html)
	{
		$result = [];
		if(preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $html, $matches, PREG_SET_ORDER)) {
			foreach($matches as $match) {
				$url = trim($match[2]);
				$result[] = $url;
			}
		}
		return array_unique($result);
	}

	private function filterInternalUrls($urls, $baseUrl)
	{
		$result = [];
		foreach ($urls as $url) {
			// omit hash urls
			if (mb_strpos($url, '#') === 0) continue;
			if ($this->isInternalURL($url, $baseUrl)) {
				$result[] = parse_url($url, PHP_URL_PATH);
			}
		}
		return array_unique($result);
	}

	private function isInternalURL($link, $baseUrl)
	{
		// internal links may have leading slash
		if (mb_strpos($link, '/') === 0 && mb_strpos($link, '//') !== 0) {
			return true;
		}
		if (mb_strpos($link, $baseUrl) === 0) {
			return true;
		}

		$linkHost = parse_url($link, PHP_URL_HOST);
		$baseHost = parse_url($baseUrl, PHP_URL_HOST);
		if (!empty($linkHost) && !empty($baseHost) && $linkHost == $baseHost) {
			return true;
		}

		return false;
	}

}
