<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
     * @return null
     */
    public function setEvent(EventInterface $event);

    /**
     * Collector Priority.
     *
     * @return Event
     */
    public function getEvent();
}
