<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Collector\CollectorInterface;
use Laminas\DeveloperTools\Collector\EventCollectorInterface;
use Laminas\DeveloperTools\Profiler;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

use function array_map;
use function array_values;

/**
 * Listens to defined events to allow event-level collection of statistics.
 *
 * @since 0.0.3
 */
class EventLoggingListenerAggregate
{
    /** @var EventCollectorInterface[] */
    protected $collectors;

    /** @var string[] The event identifiers to collect */
    protected $identifiers;

    /**
     * @param EventCollectorInterface[] $collectors
     * @param string[]                                                $identifiers
     */
    public function __construct(array $collectors, array $identifiers)
    {
        $this->collectors  = array_map(
            static fn(CollectorInterface $collector) => $collector,
            $collectors
        );
        $this->identifiers = array_values(array_map(
            static fn($identifier): string => (string) $identifier,
            $identifiers
        ));
    }

    /**
     * Attach events to a shared event manager
     *
     * @return void
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->identifiers as $id) {
            $events->attach($id, '*', [$this, 'onCollectEvent'], Profiler::PRIORITY_EVENT_COLLECTOR);
        }
    }

    /**
     * Detach events from a shared event manager
     *
     * @return void
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        // can't be detached
    }

    /**
     * Callback to process events
     *
     * @return void
     * @throws ServiceNotFoundException
     */
    public function onCollectEvent(EventInterface $event)
    {
        foreach ($this->collectors as $collector) {
            $collector->collectEvent('application', $event);
        }
    }
}
