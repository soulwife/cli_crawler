<?php

namespace Tests\Crawler;
use PHPUnit\Framework\TestCase;
use Soulwife\Crawler\Crawler;
use Soulwife\Crawler\Parser;

class CrawlerTest extends TestCase
{
    const TEST_URL = 'https://travellizy.com/';
    protected $urlContent = '';

    public function testGetUrlContent()
    {
        $object = new Crawler();
        $method = $this->getPrivateMethod('Crawler', 'getContent');

        $this->urlContent = $method->invokeArgs($object, array(self::TEST_URL));

        $this->assertStringStartsWith('<!DOCTYPE html>', $this->urlContent);
    }

    /**
    * @depends testGetUrlContent
    */
    public function testParseUrlContent()
    {
        $object = new Parser();
        $method = $this->getPrivateMethod('Parser', 'parse', [new Crawler()]);

        $urlContent = $method->invokeArgs($object, array(self::TEST_URL, $this->urlContent));

        $this->assertStringStartsWith('<!DOCTYPE html>', $urlContent);
    }

//    public function testCreateReport()
//    {
//        $currentDate = new \DateTime();
//        $this->AssertFileExists(__DIR__ . '/../report' . $currentDate->format('d.m.Y') . '.html');
//    }
//
//    /**
//     * @depends testCreateReport
//     */
//    public function testReportFileExists()
//    {
//        $currentDate = new \DateTime();
//        $this->AssertFileExists(__DIR__ . '/../report' . $currentDate->format('d.m.Y') . '.html');
//    }

    /**
     * getPrivateMethod
     *
     * @param 	string $className
     * @param 	string $methodName
     * @return	ReflectionMethod
     */
    public function getPrivateMethod( $className, $methodName, $params = [] ) {
        $reflector = new ReflectionClass( $className );
        if ($params) {
            $reflector->newInstanceArgs($params);
        }

        $method = $reflector->getMethod( $methodName );
        $method->setAccessible( true );

        return $method;
    }

}
