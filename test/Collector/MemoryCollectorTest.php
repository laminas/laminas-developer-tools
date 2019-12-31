<?php
namespace LaminasTest\DeveloperTools\Collector;

use Laminas\DeveloperTools\Collector\MemoryCollector;

class MemoryCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollector()
    {
        $collector = new MemoryCollector();

        $mvcEvent = $this->getMockBuilder("Laminas\Mvc\MvcEvent")
            ->getMock();

        $collector->collect($mvcEvent);
        $this->assertInternalType("integer", $collector->getMemory());
    }
}
