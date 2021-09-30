<?php

namespace LaminasTest\DeveloperTools\Collector;

use Laminas\DeveloperTools\Collector\ConfigCollector;
use Laminas\Mvc;
use Laminas\ServiceManager;
use PHPUnit\Framework\TestCase;

class ConfigCollectorTest extends TestCase
{
    public function testCollect()
    {
        $collector = new ConfigCollector();

        $application = $this->getMockBuilder(Mvc\Application::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serviceManager = $this->getMockBuilder(ServiceManager\ServiceManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $application
            ->expects($this->once())
            ->method("getServiceManager")
            ->willReturn($serviceManager);
        $mvcEvent = $this->getMockBuilder(Mvc\MvcEvent::class)
            ->getMock();

        $mvcEvent->method("getApplication")->willReturn($application);

        $collector->collect($mvcEvent);
    }
}
