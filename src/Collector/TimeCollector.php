<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\DeveloperTools\Collector;

use Laminas\DeveloperTools\EventLogging\EventContextProvider;
use Laminas\EventManager\Event;
use Laminas\Mvc\MvcEvent;

/**
 * Time Data Collector.
 */
class TimeCollector extends AbstractCollector implements EventCollectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'time';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return PHP_INT_MAX;
    }

    /**
     * {@inheritdoc}
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
     * @param string $id
     * @param Event  $event
     */
    public function collectEvent($id, Event $event)
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
            $result[$index] = $context;
            $result[$index]['elapsed'] = ($previous)
                ? ($context['time'] - $previous['time'])
                : ($context['time'] - $this->data['start']);
            $previous = prev($app);
            next($app);
        }

        return $result;
    }

    /**
     * Determine the start time
     *
     * @param MvcEvent $mvcEvent
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
