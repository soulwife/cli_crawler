<?php

namespace Tests\Crawler;
use PHPUnit\Framework\TestCase;
use Soulwife\Crawler\Crawler;

class CrawlerTest extends TestCase
{
    const TEST_URL = 'https://travellizy.com/';

    public function testGetUrlContent()
    {
        $object = new Crawler();
        $method = $this->getPrivateMethod('Soulwife\Crawler\Crawler', 'getContent');

        $this->urlContent = $method->invokeArgs($object, [self::TEST_URL]);

        $this->assertStringStartsWith('<!DOCTYPE html>', $this->urlContent);
    }

    /**
    * @depends testGetUrlContent
    */
    public function testGetDomainData()
    {
        $crawler = new Crawler();
        $crawler->addLink(self::TEST_URL);
        $method = $this->getPrivateMethod('Soulwife\Crawler\Crawler', 'getDomainData');
        $method->invokeArgs($crawler, []);

        $this->assertGreaterThan(0, count($crawler->getCrawledLinks()));
    }

    /**
     * @depends testGetDomainData
     */
    public function testCreateReport()
    {
        $crawler = new Crawler();
        $crawler->addLink(self::TEST_URL);
        $method = $this->getPrivateMethod('Soulwife\Crawler\Crawler', 'getDomainData');
        $method->invokeArgs($crawler, []);

        $this->assertGreaterThan(0, count($crawler->getHtmlReport()->getPageData()));
    }

    /**
     * @depends testCreateReport
     */
    public function testReportFileExists()
    {
        $currentDate = new \DateTime();
        $this->AssertFileExists(__DIR__ . '/../report_' . $currentDate->format('d.m.Y') . '.html');
    }

    /**
     * getPrivateMethod
     *
     * @param 	string $className
     * @param 	string $methodName
     * @return	\ReflectionMethod
     */
    public function getPrivateMethod( $className, $methodName, $params = [] ) {
        $reflector = new \ReflectionClass($className);
        if ($params) {
            $reflector->newInstanceArgs($params);
        }

        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

}
