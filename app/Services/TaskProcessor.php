<?php
/**
 * Description of TaskProcessor.php
 * @copyright Copyright (c) THREE POINT PRODUCTIONS, LLC
 * @author    Egor Gerasimchuk <egerasimchuk@dev.football.com>
 */

namespace App\Services;


use App\Models\Task;
use Roumen\Sitemap\Sitemap;

class TaskProcessor
{

    /**
     * @param Task $task
     * @return bool
     */
    public function process(Task $task)
    {
        $task->status = Task::STATUS_PROCESSING;
        $task->update();

        $baseURL = $task->url;
		$html = $this->retrieveHtml($baseURL);
        if (!$html) {
            $this->handleNotReachableSite($task);
            return true;
        }
		$urls = $this->retrieveAllURLs($html);
		$urls = $this->filterInternalUrls($urls, $baseURL);

        if (empty($urls)) {
            $this->handleEmptyURLs($task);
            return true;
        }

		$fileName = $this->generateSiteMap($urls, $baseURL);
        $task->status = Task::STATUS_COMPLETED;
        $task->filename = $fileName;
        $task->update();

        return true;
    }

    /**
     * @param Task $task
     * @return Task
     */
    private function handleEmptyURLs(Task $task)
    {
        $task->status = Task::STATUS_FAILED;
        $task->errorReason = 'There are no internal links on this site. Please try another site';

        $task->update();
        return $task;
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function handleNotReachableSite(Task $task)
    {
        $task->status = Task::STATUS_FAILED;
        $task->errorReason = 'Site is not available. Please try another site';

        $task->update();
        return $task;
    }

    /**
     * @param $baseURL
     * @return string
     */
    private function retrieveHtml($baseURL)
    {
        // probably it is better to add curl request to check whether site is available
        return @file_get_contents($baseURL);
    }

    /**
     * @param $urls
     * @param $baseURL
     * @return string
     */
    private function generateSiteMap($urls, $baseURL)
    {
        $baseURL = rtrim($baseURL, '/');
        /** @var Sitemap $sitemap */
        $sitemap = \App::make("sitemap");
        foreach ($urls as $url) {
            $sitemap->add("{$baseURL}{$url}", date('Y-m-d'), 1, 'daily');
        }
        $fileName = "assets/sitemaps/" . md5($baseURL);
        $sitemap->store('xml', $fileName);
        return $fileName . ".xml";
    }
    private function generateFileName($baseURL)
    {
        return "assets/sitemaps/" . md5($baseURL);
    }

    /**
     * @param $html
     * @return array
     */
    private function retrieveAllURLs($html)
    {
        $result = [];
        if (preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $url = trim($match[2]);
                $result[] = $url;
            }
        }
        return array_unique($result);
    }

    /**
     * @param $urls
     * @param $baseUrl
     * @return array
     */
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

    /**
     * @param $link
     * @param $baseUrl
     * @return bool
     */
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