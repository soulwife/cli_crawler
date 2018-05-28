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
     *
     *
     * @var array
     */
    protected $crawledLinks = [];


    /**
     *
     *
     * @var string
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
        $domain = getenv("url") ?? null;

        if (! $domain) {
            die("Please specify a url. Example: url=http://example.com php crawler.php\n");
        }

        $this->parser = new Parser($this);
        $this->htmlReport = new HtmlReport();
        $this->parser->setBaseURL($domain);
        $this->addLink($this->parser->getBaseUrl());
        $this->getDomainData();
        $this->htmlReport->createReport();

    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getHtmlReport()
    {
        return $this->htmlReport;
    }

    public function addLink($link)
    {
        return $this->links[] = $link;
    }

    public function getCrawledLinks()
    {
        return $this->crawledLinks;
    }

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
     * Get content.
     *
     * @param string $url
     *
     * @return string The status of the response.
     */
    protected function getContent($url)
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
     * Start the crawler.
     *
     * @return void
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

