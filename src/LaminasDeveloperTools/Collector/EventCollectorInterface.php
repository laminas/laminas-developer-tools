<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Collector;

/**
 * Event Data Collector Interface.
 *
 */
interface EventCollectorInterface
{
    /**
     * Saves the current time in microseconds for an specific event.
     *
     * @param string                          $id
     * @param \Laminas\EventManager\Event|string $event
     */
    public function collectEvent($id, $event);
}