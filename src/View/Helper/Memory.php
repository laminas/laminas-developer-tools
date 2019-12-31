<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\View\Helper;

use Laminas\View\Helper\AbstractHelper;

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
        }

        if (($size / 1024) < 1024) {
            return sprintf('%.0f KB', $size / 1024);
        }

        return sprintf('%.' . $precision . 'f MB', $size / 1024 / 1024);
    }
}
