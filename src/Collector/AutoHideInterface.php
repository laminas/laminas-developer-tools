<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
