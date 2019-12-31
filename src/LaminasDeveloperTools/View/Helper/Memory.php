<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * @copyright  Copyright (c) 2005-2014 Laminas (https://www.zend.com)
 * @license    https://getlaminas.org/license/new-bsd     New BSD License
 */
class Memory extends AbstractHelper
{
    /**
     * Returns the formatted memory.
     *
     * @param  integer $size
     * @param  integer $precision Only used for MegaBytes
     * @return string
     */
    public function __invoke($size, $precision = 2)
    {
        if ($size < 1024) {
            return sprintf('%d B', $size);
        } elseif (($size / 1024) < 1024) {
            return sprintf('%.0f KB', $size / 1024);
        } else {
            return sprintf('%.' . $precision . 'f MB', $size / 1024 / 1024);
        }
    }
}
