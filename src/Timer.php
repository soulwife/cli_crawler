<?php

namespace Soulwife\Crawler;

class Timer
{

    /**
     *
     *
     * @var float
     */
    static protected $startTime = 0;

    /**
     *
     *
     * @var float
     */
    static protected $finishTime = 0;

    /**
     *
     *
     * @var float
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

    /**
     * @return float
     */
    static public function getTime(): float
    {
        return static::$finishTime - static::$startTime;
    }

}