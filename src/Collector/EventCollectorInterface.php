<?php

declare(strict_types=1);

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
     */
    public function collectEvent($id, EventInterface $event);
}
