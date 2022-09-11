<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Profiler;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ResponseInterface;

use function is_callable;

/**
 * Flush Listener
 *
 * Listens to the MvcEvent::EVENT_FINISH event with a low priority and flushes the page.
 */
class FlushListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @inheritDoc
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
