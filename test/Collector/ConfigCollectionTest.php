<?php
namespace LaminasTest\DeveloperTools\Collector;

use Laminas\DeveloperTools\Collector\ConfigCollector;

class ConfigCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollect()
    {
        $collector = new ConfigCollector();

        $application = $this->getMockBuilder("Laminas\Mvc\Application")
            ->disableOriginalConstructor()
            ->getMock();
        $serviceManager = $this->getMockBuilder("Laminas\ServiceManager\ServiceManager")
            ->disableOriginalConstructor()
            ->getMock();

        $application
            ->expects($this->once())
            ->method("getServiceManager")
            ->willReturn($serviceManager);
        $mvcEvent = $this->getMockBuilder("Laminas\Mvc\MvcEvent")
            ->getMock();

        $mvcEvent->method("getApplication")->willReturn($application);

        $collector->collect($mvcEvent);
    }
}
