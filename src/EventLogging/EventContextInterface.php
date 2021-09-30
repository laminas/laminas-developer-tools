<?php

namespace Laminas\DeveloperTools\EventLogging;

use Laminas\EventManager\EventInterface;

/**
 * Interface for classes that want to provide event context in the event-level collectors.
 *
 * @author Mark Garrett <mark.garrett@allcarepharmacy.com>
 */
interface EventContextInterface
{
    /**
     * Sets the event.
     *
     * @param EventInterface $event
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
