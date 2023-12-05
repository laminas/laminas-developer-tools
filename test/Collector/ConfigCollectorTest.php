<?php

declare(strict_types=1);

namespace LaminasTest\DeveloperTools\Collector;

use Laminas\DeveloperTools\Collector\ConfigCollector;
use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

use function serialize;
use function unserialize;

class ConfigCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $collector = new ConfigCollector();

        $config            = ['main' => 'config'];
        $applicationConfig = ['main' => 'config'];

        $serviceManager = new ServiceManager([
            'services' => [
                'config'            => $config,
                'ApplicationConfig' => $applicationConfig,
            ],
        ]);

        $application = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();
        $application
            ->expects($this->once())
            ->method("getServiceManager")
            ->willReturn($serviceManager);
        $mvcEvent = $this->getMockBuilder(MvcEvent::class)
            ->getMock();
        $mvcEvent->method("getApplication")->willReturn($application);

        $collector->collect($mvcEvent);

        self::assertEqualsCanonicalizing($config, $collector->getConfig());
        self::assertEqualsCanonicalizing($applicationConfig, $collector->getApplicationConfig());
    }

    public function testSerialize(): void
    {
        $collector = new ConfigCollector();

        $config            = ['main' => 'config'];
        $applicationConfig = ['main' => 'config'];

        $serviceManager = new ServiceManager([
            'services' => [
                'config'            => $config,
                'ApplicationConfig' => $applicationConfig,
            ],
        ]);

        $application = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();
        $application
            ->expects($this->once())
            ->method("getServiceManager")
            ->willReturn($serviceManager);
        $mvcEvent = $this->getMockBuilder(MvcEvent::class)
            ->getMock();
        $mvcEvent->method("getApplication")->willReturn($application);

        $collector->collect($mvcEvent);

        $serialized   = serialize($collector);
        $unserialized = unserialize($serialized);

        self::assertInstanceOf(ConfigCollector::class, $unserialized);

        self::assertEqualsCanonicalizing($config, $collector->getConfig());
        self::assertEqualsCanonicalizing($applicationConfig, $collector->getApplicationConfig());
    }
}
