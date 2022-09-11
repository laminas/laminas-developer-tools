<?php

declare(strict_types=1);

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
     */
    public function collect(MvcEvent $mvcEvent);
}
