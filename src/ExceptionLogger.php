<?php
/**
 * Created by PhpStorm.
 * User: constellation
 * Date: 5/28/18
 * Time: 19:38
 */

namespace Soulwife\Crawler;


class ExceptionLogger {

    /**
     * Log exceptions to logger.log file
     *
     * @param \Throwable $e
     */
    public static function log(\Throwable $e)
    {
        $output = 'File: ' . $e->getFile() . PHP_EOL;
        $output .= 'Line: ' . $e->getLine() . PHP_EOL;
        $output .= 'Message: ' . PHP_EOL . $e->getMessage() . PHP_EOL;
        $output .= 'Stack Trace:' . PHP_EOL . $e->getTraceAsString() . PHP_EOL;

        error_log("Uncaught exception: " . $output, 3, __DIR__ . '/../logger.log');
    }
}