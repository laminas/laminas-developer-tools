<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use Laminas\DeveloperTools\EventLogging\EventContextProvider;
use Laminas\EventManager\EventInterface;
use Laminas\Mvc\MvcEvent;

use function defined;
use function microtime;
use function next;
use function prev;

use const PHP_INT_MAX;
use const PHP_VERSION_ID;

/**
 * Time Data Collector.
 */
class TimeCollector extends AbstractCollector implements EventCollectorInterface
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'time';
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return PHP_INT_MAX;
    }

    /**
     * @inheritDoc
     */
    public function collect(MvcEvent $mvcEvent)
    {
        $start = $this->marshalStartTime($mvcEvent);

        if (! isset($this->data)) {
            $this->data = [];
        }

        $this->data['start'] = $start;
        $this->data['end']   = microtime(true);
    }

    /**
     * Saves the current time in microseconds for a specific event.
     *
     * @param string         $id
     */
    public function collectEvent($id, EventInterface $event)
    {
        $contextProvider   = new EventContextProvider($event);
        $context['time']   = microtime(true);
        $context['name']   = $contextProvider->getEvent()->getName();
        $context['target'] = $contextProvider->getEventTarget();
        $context['file']   = $contextProvider->getEventTriggerFile();
        $context['line']   = $contextProvider->getEventTriggerLine();

        if (! isset($this->data['event'][$id])) {
            $this->data['event'][$id] = [];
        }

        $this->data['event'][$id][] = $context;
    }

    /**
     * Returns the total execution time.
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->data['start'];
    }

    /**
     * Returns the total execution time.
     *
     * @return float
     */
    public function getExecutionTime()
    {
        return $this->data['end'] - $this->data['start'];
    }

    /**
     * Event times collected?
     *
     * @return bool
     */
    public function hasEventTimes()
    {
        return isset($this->data['event']);
    }

    /**
     * Returns the detailed application execution time.
     *
     * @return array
     */
    public function getApplicationEventTimes()
    {
        $result = [];

        if (! isset($this->data['event']['application'])) {
            return $result;
        }

        $app = $this->data['event']['application'];

        $previous = null;
        foreach ($app as $index => $context) {
            $result[$index]            = $context;
            $result[$index]['elapsed'] = $previous
                ? $context['time'] - $previous['time']
                : $context['time'] - $this->data['start'];
            $previous                  = prev($app);
            next($app);
        }

        return $result;
    }

    /**
     * Determine the start time
     *
     * @return float
     */
    private function marshalStartTime(MvcEvent $mvcEvent)
    {
        if (PHP_VERSION_ID >= 50400) {
            return $mvcEvent->getRequest()->getServer()->get('REQUEST_TIME_FLOAT');
        }

        if (defined('REQUEST_MICROTIME')) {
            return REQUEST_MICROTIME;
        }

        return $mvcEvent->getRequest()->getServer()->get('REQUEST_TIME');
    }
}
