<?php
/**
 * Created by PhpStorm.
 * User: constellation
 * Date: 5/28/18
 * Time: 13:24
 */

namespace Soulwife\Crawler;


class HtmlReport extends Report
{

    protected function createTable()
    {
        $tableRows = $this->createRows();

        return $tableRows ? "<table border='1'><tr><th>URL</th><th>Images</th><th>Time (s)</th></tr> {$tableRows} </table>" : 'There is no data';
    }

    protected function createHeader()
    {
        return '<!DOCTYPE html><html lang="en"><body>';
    }

    protected function createFooter()
    {
        return '</body></html>';
    }

    /**
     * Create table rows
     * @return string
     */
    protected function createRows() {
        $tableRows = "";
        foreach ($this->pageData as $row) {
            $tableRows .= "<tr>" . implode("", $this->createRow($row)) . "</tr>";
        }

        return $tableRows;
    }

    /**
     *
     * @param array $rowElements
     * @return string
     */
    protected function createRow($rowElements) {
        var_dump($rowElements);
        array_walk_recursive($rowElements, function(&$cell) {
            $cell =  "<td>{$cell}</td>";
        });

        return $rowElements;

    }



}