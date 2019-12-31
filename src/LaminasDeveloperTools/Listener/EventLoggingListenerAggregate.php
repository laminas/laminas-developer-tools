<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\DeveloperTools\ReportInterface;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\EventManager\SharedListenerAggregateInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Listens to defined events to allow event-level collection of statistics.
 *
 * @author Mark Garrett <mark@moderndeveloperllc.com>
 * @since 0.0.3
 */
class EventLoggingListenerAggregate implements SharedListenerAggregateInterface
{

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     *
     * @var Options
     */
    protected $options;

    /**
     *
     * @var array
     */
    protected $listeners = array();

    /**
     *
     * @var ReportInterface
     */
    protected $report;

    /**
     * Constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param Options $options
     * @param ReportInterface $report
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Options $options, ReportInterface $report)
    {
        $this->options        = $options;
        $this->serviceLocator = $serviceLocator;
        $this->report         = $report;
    }

    /**
     * @inheritdoc
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $identifiers = array_values($this->options->getEventIdentifiers());
        $this->listeners[] = $events->attach(
            $identifiers,
            '*',
            array($this,'onCollectEvent'),
            Profiler::PRIORITY_EVENT_COLLECTOR
        );
    }

    /**
     * @inheritdoc
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Callback to process events
     *
     * @param Event $event
     * @throws ServiceNotFoundException
     */
    public function onCollectEvent(Event $event)
    {
        $strict = $this->options->isStrict();
        $collectors = $this->options->getEventCollectors();

        foreach ($collectors as $name => $collector) {
            if ($this->serviceLocator->has($collector)) {
                $this->serviceLocator->get($collector)->collectEvent('application', $event);
            } else {
                $error = sprintf('Unable to fetch or create an instance for %s.', $collector);
                if ($strict === true) {
                    throw new ServiceNotFoundException($error);
                } else {
                    $this->report->addError($error);
                }
            }
        }
    }
}
