<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\EventLogging;

use Laminas\EventManager\EventInterface;

/**
 * Interface for classes that want to provide event context in the event-level collectors.
 */
interface EventContextInterface
{
    /**
     * Sets the event.
     *
     * @return void
     */
    public function setEvent(EventInterface $event);

    /**
     * Collector Priority.
     *
     * @return EventInterface
     */
    public function getEvent();
}
