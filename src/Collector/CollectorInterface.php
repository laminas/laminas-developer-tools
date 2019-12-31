<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Collector;

use Laminas\Mvc\MvcEvent;

/**
 * Collector Interface.
 */
interface CollectorInterface
{
    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName();

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority();

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     */
    public function collect(MvcEvent $mvcEvent);
}
