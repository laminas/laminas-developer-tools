<?php

declare(strict_types=1);

namespace LaminasTest\DeveloperTools;

use Laminas\DeveloperTools\Module;
use PHPUnit\Framework\TestCase;

use function serialize;
use function unserialize;

class ModuleTest extends TestCase
{
    public function testGetConfig(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertIsArray($config);
    }

    public function testConfigSerialization(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertSame($config, unserialize(serialize($config)));
    }
}
