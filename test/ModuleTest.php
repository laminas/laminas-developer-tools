<?php

namespace LaminasTest\DeveloperTools;

use Laminas\DeveloperTools\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testGetConfig()
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertIsArray($config);
    }

    public function testConfigSerialization()
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertSame($config, unserialize(serialize($config)));
    }
}
