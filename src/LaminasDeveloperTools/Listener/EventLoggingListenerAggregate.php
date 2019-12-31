<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Collector\CollectorInterface;
use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\EventManager\SharedListenerAggregateInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Listens to defined events to allow event-level collection of statistics.
 *
 * @author Mark Garrett <mark@moderndeveloperllc.com>
 * @since 0.0.3
 */
class EventLoggingListenerAggregate implements SharedListenerAggregateInterface
{
    /**
     * @var \Laminas\DeveloperTools\Collector\EventCollectorInterface[]
     */
    protected $collectors;

    /**
     * @var Options
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param \Laminas\DeveloperTools\Collector\EventCollectorInterface[] $collectors
     * @param string[]                                                $identifiers
     */
    public function __construct(array $collectors, array $identifiers)
    {
        $this->collectors = array_map(
            function (CollectorInterface $collector) {
                return $collector;
            },
            $collectors
        );
        $this->identifiers = array_values(array_map(
            function ($identifier) {
                return (string) $identifier;
            },
            $identifiers
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach($this->identifiers, '*', array($this,'onCollectEvent'), Profiler::PRIORITY_EVENT_COLLECTOR);
    }

    /**
     * {@inheritdoc}
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        // can't be detached
    }

    /**
     * Callback to process events
     *
     * @param Event $event
     *
     * @return bool
     *
     * @throws ServiceNotFoundException
     */
    public function onCollectEvent(Event $event)
    {
        foreach ($this->collectors as $collector) {
            $collector->collectEvent('application', $event);
        }

        return true; // @TODO workaround, to be removed when https://github.com/zendframework/zf2/pull/6147 is fixed
    }
}
