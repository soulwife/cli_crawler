<?php
/**
 * Created by PhpStorm.
 * User: constellation
 * Date: 5/28/18
 * Time: 16:48
 */

namespace Soulwife\Crawler;


abstract class Report
{
    const AMOUNT_OF_IMAGES_FIELD = 'amountOfImages';
    const PAGE_URL_FIELD = 'pageUrl';
    const PARSE_TIME_FIELD = 'parseTime';

    const FILE_PATH = './';
    const FILE_EXT = 'html';

    /**
     *
     *
     * @var array
     */
    protected $pageData = [];

    abstract protected function createTable();

    abstract protected function createHeader();

    abstract protected function createFooter();

    protected function sortByAmountOfImages()
    {
        usort($this->pageData, function($a, $b){
            if ($a[self::AMOUNT_OF_IMAGES_FIELD] == $b[self::AMOUNT_OF_IMAGES_FIELD]) {
                return 0;
            }

            return ($a[self::AMOUNT_OF_IMAGES_FIELD] > $b[self::AMOUNT_OF_IMAGES_FIELD]) ? -1 : 1;
        });
    }

    public function getPageData()
    {
        return $this->pageData;
    }

    public function addPageData($url, $amountOfImages)
    {
        $this->pageData[] = [
            self::PAGE_URL_FIELD => $url,
            self::AMOUNT_OF_IMAGES_FIELD => $amountOfImages,
            self::PARSE_TIME_FIELD => number_format(Timer::getTime(), 4)
        ];
    }

    protected function addDataToReport()
    {
        $report = $this->createHeader();
        $report .= $this->createTable();
        $report .= $this->createFooter();

        return $report;
    }

    public function createReport()
    {
        $currentDate = new \DateTime();
        $fileHandle = null;
        $this->sortByAmountOfImages();

        try {
            $report = $this->addDataToReport();
            $fileHandle = fopen(self::FILE_PATH . 'report_' . $currentDate->format('d.m.Y') . '.' . self::FILE_EXT, 'w');
            fwrite($fileHandle, $report);
        }
        catch (\Throwable $e) {
            ExceptionLogger::log($e);
        }
        finally {
            fclose($fileHandle);
        }
    }


}