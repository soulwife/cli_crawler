<?php

namespace Soulwife\Crawler;


class Parser
{

    /**
     * Crawler object
     *
     * @var Crawler
     */
    protected $crawler;

    /**
     * Initial url to get absolute paths
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Initial url without protocol
     *
     * @var string
     */
    protected $baseUrlWithoutProtocol;


    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Define initial url to get absolute paths and filter internal links
     *
     * @param string $url
     */
    public function setBaseURL(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            die("Please provide a valid url. Example: http://example.com\n");
        }

        $parsed = parse_url($url);
        $this->baseUrlWithoutProtocol = $parsed['host'] . '/';

        $this->baseUrl = "{$parsed['scheme']}://{$parsed['host']}";
        $this->baseUrl .= $parsed['port'] ? ":{$parsed['port']}" : '';
        $this->baseUrl .= '/';

        $urlPathQuery = $parsed['path'] ? "{$parsed['path']}/" : '';
        $urlPathQuery.= $parsed['query'] ? "?{$parsed['query']}" : '';

        $this->baseUrl = $this->baseUrl.trim($urlPathQuery, '/');
    }

    /**
     * Get initial url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Define if link is internal and parse it
     *
     * @param string $link
     * @param string $parentLink
     * @return bool
     */
    protected function parseLink(string $link, string $parentLink): bool
    {
        if (! $link || $link === '/' || $link === 'javascript:;') {
            return false;
        }

        // Relative to absolute paths
        $parentLink = trim($parentLink, '/');
        if ($link[0] == '.') {
            $link = "{$parentLink}/".ltrim($link, '/');
        }

        if (strpos($link, 'http') !== false) {
            // Ignore external URLs
            if (strpos($link, $this->baseUrlWithoutProtocol) === false) {
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
     * Scan domain pages for links and add them to the $links. Get data, that necessary for report
     *
     * @param string $url
     * @param string $content
     *
     * @return void
     */
    public function parse(string $url, string $content)
    {
        if (! $content) {
            return;
        }
        try {
            libxml_use_internal_errors(true);
            //start timer to get page working time
            Timer::startTimer();
            $dom = new \DOMDocument;
            $dom->loadHTML($content);
            $links = $dom->getElementsByTagName('a');

            //process domain pages
            foreach ($links as $link) {
                $href = $link->getAttribute("href");
                $this->parseLink($href, $url);
            }

            //get amount of images
            $images = $dom->getElementsByTagName('img');
            Timer::stopTimer();
            libxml_use_internal_errors(false);
            $this->crawler->getHtmlReport()->addPageData($url, $images->length);

        } catch (\Throwable $e) {
            ExceptionLogger::log($e);
        }
    }
}