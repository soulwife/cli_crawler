<?php

namespace Soulwife\Crawler;


class Timer
{

    /**
     *
     *
     * @var string
     */
    static protected $startTime = 0;

    /**
     *
     *
     * @var string
     */
    static protected $finishTime = 0;

    /**
     *
     *
     * @var string
     */
    static protected $totalTime = 0;


    static public function startTimer()
    {
        static::$startTime = microtime(true);
    }

    static public function stopTimer()
    {
        static::$finishTime = microtime(true);
    }

    static public function getTime()
    {
        return static::$finishTime - static::$startTime;
    }

}