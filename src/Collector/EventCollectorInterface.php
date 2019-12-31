<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
