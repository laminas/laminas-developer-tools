<?php

namespace Laminas\DeveloperTools\Collector;

use Laminas\EventManager\EventInterface;

/**
 * Event Data Collector Interface.
 */
interface EventCollectorInterface
{
    /**
     * Collects event-level information
     *
     * @param string         $id
     * @param EventInterface $event
     */
    public function collectEvent($id, EventInterface $event);
}
