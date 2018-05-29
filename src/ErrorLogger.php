<?php

namespace Soulwife\Crawler;


class ErrorLogger {

    /**
     * Log errors to logger.log file
     *
     * @param string $message
     */
    public static function log(string $message)
    {
        error_log($message . PHP_EOL, 3, __DIR__ . '/../logger.log');
    }
}