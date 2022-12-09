<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools;

use DateTime;
use DateTimeZone;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\PriorityQueue;

use function sprintf;
use function uniqid;

use const PHP_INT_MAX;

class Profiler implements EventManagerAwareInterface
{
    /**
     * Event collector listener priority.
     *
     * @var int
     */
    public const PRIORITY_EVENT_COLLECTOR = PHP_INT_MAX;

    /**
     * FirePHP listener priority.
     *
     * @var int
     */
    public const PRIORITY_FIREPHP = 500;

    /**
     * Flush listener priority.
     * Note: The Priority must be lower than PRIORITY_PROFILER!
     *
     * @var int
     */
    public const PRIORITY_FLUSH = -9400;

    /**
     * Profiler listener priority.
     *
     * @var int
     */
    public const PRIORITY_PROFILER = -9500;

    /**
     * Storage listener priority.
     *
     * @var int
     */
    public const PRIORITY_STORAGE = 100;

    /**
     * Toolbar listener priority.
     *
     * @var int
     */
    public const PRIORITY_TOOLBAR = 500;

    /** @var bool */
    protected $strict;

    /** @var ProfilerEvent */
    protected $event;

    /** @var ReportInterface */
    protected $report;

    /** @var PriorityQueue */
    protected $collectors;

    /** @var EventManagerInterface */
    protected $eventManager;

    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    /**
     * Set the error mode.
     *
     * @param  bool $mode
     * @return self
     */
    public function setErrorMode($mode)
    {
        $this->strict = $mode;
        return $this;
    }

    /**
     * Set the profiler event object.
     *
     * @return self
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * Returns the profiler event object.
     *
     * @return ProfilerEvent
     */
    public function getEvent()
    {
        if (! isset($this->event)) {
            $this->event = new ProfilerEvent();
            $this->event->setTarget($this);
            $this->event->setProfiler($this);
        }

        return $this->event;
    }

    /**
     * Set the event manager instance
     *
     * @return self
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers([self::class, static::class, 'profiler']);
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * Get the event manager instance
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Adds a collector.
     *
     * @param  Collector\CollectorInterface $collector
     * @return self
     * @throws Exception\CollectorException
     */
    public function addCollector($collector)
    {
        if (! isset($this->collectors)) {
            $this->collectors = new PriorityQueue();
        }

        if ($collector instanceof Collector\CollectorInterface) {
            $this->collectors->insert($collector, $collector->getPriority());
            return $this;
        }

        $error = sprintf('%s must implement CollectorInterface.', $collector::class);
        if ($this->strict === true) {
            throw new Exception\CollectorException($error);
        }
        $this->report->addError($error);

        return $this;
    }

    /**
     * Runs all collectors.
     *
     * @triggers ProfilerEvent::EVENT_COLLECTED
     * @return   Profiler
     * @throws   Exception\ProfilerException
     */
    public function collect(MvcEvent $mvcEvent)
    {
        $this->report->setToken(uniqid('zdt'))
            ->setUri($mvcEvent->getRequest()->getUriString())
            ->setMethod($mvcEvent->getRequest()->getMethod())
            ->setTime(new DateTime('now', new DateTimeZone('UTC')))
            ->setIp($mvcEvent->getRequest()->getServer()->get('REMOTE_ADDR'));

        if (isset($this->collectors)) {
            foreach ($this->collectors as $collector) {
                $collector->collect($mvcEvent);
                $this->report->addCollector($collector);
            }

            $event = $this->getEvent();
            $event->setName(ProfilerEvent::EVENT_COLLECTED);
            $this->eventManager->triggerEvent($event);

            return $this;
        }

        if ($this->strict === true) {
            throw new Exception\ProfilerException('There is nothing to collect.');
        }

        $this->report->addError('There is nothing to collect.');

        return $this;
    }
}
