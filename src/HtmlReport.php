<?php

namespace Soulwife\Crawler;


class HtmlReport extends Report
{

    /**
     * Create report table from $pageData
     *
     * @return string
     */
    protected function createTable(): string
    {
        $tableRows = $this->createRows();

        return $tableRows ? "<table border='1'><tr><th>URL</th><th>Images</th><th>Time (s)</th></tr> {$tableRows} </table>" : 'There is no data. Please see logger.log';
    }

    /**
     * Create html page header
     *
     * @return string
     */
    protected function createHeader(): string
    {
        return '<!DOCTYPE html><html lang="en"><body>';
    }

    /**
     * Create html page footer
     *
     * @return string
     */
    protected function createFooter(): string
    {
        return '</body></html>';
    }

    /**
     * Create table rows from $pageData
     *
     * @return string
     */
    protected function createRows(): string
    {
        $tableRows = "";
        foreach ($this->pageData as $row) {
            $tableRows .= "<tr>" . implode("", $this->createRow($row)) . "</tr>";
        }

        return $tableRows;
    }

    /**
     * Create one row for report table
     *
     * @param array $rowElements
     * @return array
     */
    protected function createRow($rowElements): array
    {
        array_walk_recursive($rowElements, function(&$cell) {
            $cell =  "<td>{$cell}</td>";
        });

        return $rowElements;

    }

}