<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\View\Helper;

use Laminas\View\Helper\AbstractHelper;

use function sprintf;

class Time extends AbstractHelper
{
    /**
     * Returns the formatted time.
     *
     * @param  integer|float $time
     * @param  integer       $precision Will only be used for seconds.
     * @return string
     */
    public function __invoke($time, $precision = 2)
    {
        if ($time === 0) {
            return '0 s';
        }

        if ($time >= 1) {
            return sprintf('%.' . $precision . 'f s', $time);
        }

        if ($time * 1000 >= 1) {
            return sprintf('%.' . $precision . 'f ms', $time * 1000);
        }

        return sprintf('%.' . $precision . 'f µs', $time * 1_000_000);
    }
}
