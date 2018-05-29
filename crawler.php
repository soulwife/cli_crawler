#!php
<?php

ini_set('display_errors','Off');

set_exception_handler(array("ExceptionLogger", "log"));
set_error_handler(array("ErrorLogger", "log"));

require_once __DIR__ . '/autoload.php';

new \Soulwife\Crawler\Crawler();