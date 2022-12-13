<?php

declare(strict_types=1);

namespace LaminasTest\DeveloperTools\EventLogging;

use Laminas\DeveloperTools\EventLogging\EventContextProvider;
use PHPUnit\Framework\TestCase;

class EventContextProviderTest extends TestCase
{
    public function testEventTriggerFileForInvokables()
    {
        $eventProviderClass = new class {
            public function __invoke() {
                return call_user_func([$this, 'get']);
            }

            private function get()
            {
                $eventProviderClass = new class {
                    public function __invoke($invokableClass, int $maxLevel, int $level = 0)
                    {
                        if ($level < $maxLevel) {
                            return $invokableClass($invokableClass, $maxLevel, ++$level);
                        }

                        return (new EventContextProvider())->getEventTriggerFile();
                    }
                };

                return $eventProviderClass($eventProviderClass, 3);
            }
        };

        $eventTriggerFile = $eventProviderClass();

        self::assertSame(
            '',
            $eventTriggerFile,
            'trigger file should be empty because user function calls do not refer to a file'
        );
    }
}
