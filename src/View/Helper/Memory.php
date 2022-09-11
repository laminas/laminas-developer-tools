<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\View\Helper;

use Laminas\View\Helper\AbstractHelper;

use function sprintf;

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
