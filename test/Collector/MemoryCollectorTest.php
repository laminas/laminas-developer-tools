<?php

namespace LaminasTest\DeveloperTools\Collector;

use Laminas\DeveloperTools\Collector\MemoryCollector;
use Laminas\Mvc;
use PHPUnit\Framework\TestCase;

class MemoryCollectorTest extends TestCase
{
    public function testCollector()
    {
        $collector = new MemoryCollector();

        $mvcEvent = $this->getMockBuilder(Mvc\MvcEvent::class)
            ->getMock();

        $collector->collect($mvcEvent);
        $this->assertIsInt($collector->getMemory());
    }
}
