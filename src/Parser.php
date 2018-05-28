<?php

namespace Soulwife\Crawler;


class Parser
{

    /**
     * The initial url to get absolute paths.
     *
     * @var string
     */
    protected $crawler;

    /**
     * The initial url to get absolute paths.
     *
     * @var string
     */
    protected $baseUrl;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Set the base url.
     *
     * @param $url
     */
    public function setBaseURL($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            die("Please provide a valid url. Example --url http://example.com\n");
        }

        $parsed = parse_url($url);
        $this->baseUrl = "{$parsed['scheme']}://{$parsed['host']}";
        $this->baseUrl .= $parsed['port'] ? ":{$parsed['port']}" : '';
        $this->baseUrl .= '/';

        $urlPathQuery = $parsed['path'] ? "{$parsed['path']}/" : '';
        $urlPathQuery.= $parsed['query'] ? "?{$parsed['query']}" : '';


        // Add the first url to the stack
        $this->baseUrl = $this->baseUrl.trim($urlPathQuery, '/');
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Parse link for processing.
     *
     * @param $link
     */
    protected function parseLink($link, $parentLink)
    {
        if (! $link || $link === '/' || $link[0] === '#' || $link === 'javascript:;') {
            return false;
        }

        // Relative to absolute paths
        $parentLink = trim($parentLink, '/');
        if ($link[0] == '.') {
            $link = "{$parentLink}/".ltrim($link, '/');
        }

        if (strpos($link, 'http') !== false) {
            // Ignore external URLs
            if (strpos($link, $this->baseUrl) === false) {
                return false;
            }
        } else {
            $link = $this->baseUrl.ltrim($link, '/');
        }

        if (! in_array($link, $this->crawler->getCrawledLinks())) {
            $this->crawler->addLink($link);
        }

        return false;
    }

    /**
     * Scan content for links and add them to the list.
     *
     * @param string $url
     * @param string $content
     *
     * @return void
     */
    public function parse($url, $content)
    {
        if (! $content) {
            return;
        }
        try {
            Timer::startTimer();
            $dom = new \DOMDocument;
            $dom->loadHTML($content);
            $links = $dom->getElementsByTagName('a');

            // Process domain pages
            foreach ($links as $link) {
                $href =  $link->getAttribute("href");
                $this->parseLink($href, $url);
            }

            $images = $dom->getElementsByTagName('img');
            Timer::stopTimer();
            $this->crawler->getHtmlReport()->addPageData($url, $images->length);

        } catch (\Throwable $e) {
            ExceptionLogger::log($e);
        }
    }
}