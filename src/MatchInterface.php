<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools;

interface MatchInterface
{
    /**
     * The (case-insensitive) name of the matcher.
     *
     * @return string
     */
    public function getName();

    /**
     * Matches the pattern against data.
     *
     * @param  string $pattern
     * @return mixed
     */
    public function matches($pattern);
}
