<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
