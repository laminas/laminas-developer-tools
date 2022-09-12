<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

/**
 * Auto hide Interface provides the ability for collectors, to specify that
 * they can be hidden.
 */
interface AutoHideInterface
{
    /**
     * Returns true if the collector can be hidden, because it is empty.
     *
     * @return bool
     */
    public function canHide();
}
