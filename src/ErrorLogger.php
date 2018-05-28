<?php
/**
 * Created by PhpStorm.
 * User: constellation
 * Date: 5/28/18
 * Time: 19:38
 */

namespace Soulwife\Crawler;


class ErrorLogger {

    public static function log($message)
    {
        error_log($message . PHP_EOL, 3, __DIR__ . '/../logger.log');
    }
}