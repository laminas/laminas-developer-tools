<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Profiler;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ResponseInterface;

/**
 * Flush Listener
 *
 * Listens to the MvcEvent::EVENT_FINISH event with a low priority and flushes the page.
 */
class FlushListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_FINISH,
            [$this, 'onFinish'],
            Profiler::PRIORITY_FLUSH
        );
    }

    /**
     * MvcEvent::EVENT_FINISH event callback
     *
     * @param MvcEvent $event
     */
    public function onFinish(MvcEvent $event)
    {
        $response = $event->getResponse();
        if (! $response instanceof ResponseInterface) {
            return;
        }

        if (is_callable([$response, 'send'])) {
            return $response->send();
        }
    }
}
