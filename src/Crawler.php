<?php

namespace Soulwife\Crawler;

class Crawler
{

    /**
     * Links to parse
     *
     * @var array
     */
    protected $links = [];

    /**
     * Crawled links
     *
     * @var array
     */
    protected $crawledLinks = [];


    /**
     * Stream context for file_get_contents
     *
     * @var resource
     */
    protected $context;

    /**
     *
     *
     * @var Parser
     */
    protected $parser;

    /**
     *
     *
     * @var HtmlReport
     */
    protected $htmlReport;


    public function __construct()
    {
        //get url from command line
        $domain = getenv("url") ?? null;

        if (! $domain) {
            die("Please specify a url. Example: url=http://example.com php crawler.php\n");
        }

        $this->parser = new Parser($this);
        $this->htmlReport = new HtmlReport();
        //define initial url to get absolute paths and filter internal links
        $this->parser->setBaseURL($domain);
        $this->addLink($this->parser->getBaseUrl());
        //crawl pages and parse data for report
        $this->getDomainData();
        //create report
        $this->htmlReport->createReport();

    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return HtmlReport
     */
    public function getHtmlReport(): HtmlReport
    {
        return $this->htmlReport;
    }

    /**
     * @param string $link
     * @return Crawler
     */
    public function addLink(string $link): Crawler
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * @return array
     */
    public function getCrawledLinks(): array
    {
        return $this->crawledLinks;
    }

    /**
     * Create stream context for file_get_contents
     *
     */
    protected function createContext()
    {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'timeout' => 1,
                'header'=>"Accept-language: en\r\n" .
                    "Cookie: curl=forever\r\n"
            ),
            'ssl' => [
                'verify_peer' => true,
                'disable_compression' => true,
            ],
        );

        $this->context = stream_context_create($opts);
    }

    /**
     * Get page content
     *
     * @param string $url
     * @return string
     */
    protected function getContent(string $url): string
    {
        try {
            $content = file_get_contents($url, false, $this->context);
            $this->crawledLinks[] = $url;

            if ($content === false) {
                ErrorLogger::log('Can not get content from the ' . $url);
            }
        } catch (\Throwable $e) {
            ExceptionLogger::log($e);
        }

        return $content ?? '';
    }


    /**
     * Crawl domain pages and parse data
     *
     */
    protected function getDomainData()
    {
        while (!empty($this->links)) {
            $link = array_shift($this->links);
            if (!in_array($link, $this->crawledLinks)) {
                $content = $this->getContent($link);
                $this->parser->parse($link, $content);
            }
        }
    }


}

