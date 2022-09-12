<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Profiler;
use Laminas\DeveloperTools\ProfilerEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Report Storage Listener
 */
class StorageListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            ProfilerEvent::EVENT_COLLECTED,
            [$this, 'onCollected'],
            Profiler::PRIORITY_STORAGE
        );
    }

    /**
     * ProfilerEvent::EVENT_COLLECTED event callback.
     */
    public function onCollected(ProfilerEvent $event)
    {
    }
}
