<?php

declare(strict_types=1);

namespace LaminasTest\DeveloperTools\EventLogging;

use Laminas\DeveloperTools\EventLogging\EventContextProvider;
use PHPUnit\Framework\TestCase;

use function array_map;

/** @covers \Laminas\DeveloperTools\EventLogging\EventContextProvider */
class EventContextProviderTest extends TestCase
{
    public function testEventTriggerFileDetectsEmptyFileLocationWhenStackFrameIsInPhpItself(): void
    {
        self::assertSame(
            [
                'stack frame on this file: EventLogging/EventContextProviderTest.php',
                'stack frame in php core: ',
                'stack frame on this file: EventLogging/EventContextProviderTest.php',
            ],
            // Important: `array_map()` needed here, as it produces a stack frame without "file" location
            array_map(
                [$this, 'callEventContextProviderWithStackTraceNestingLevelAndName'],
                [
                    'stack frame on this file',
                    'stack frame in php core',
                    'stack frame on this file',
                ],
                [
                    3,
                    4,
                    5,
                ]
            ),
            'trigger file should be empty because user function calls do not refer to a file'
        );
    }

    private function callEventContextProviderWithStackTraceNestingLevelAndName(string $name, int $level): string
    {
        if ($level > 0) {
            return $this->callEventContextProviderWithStackTraceNestingLevelAndName($name, $level - 1);
        }

        return $name . ': ' . (new EventContextProvider())->getEventTriggerFile();
    }
}
