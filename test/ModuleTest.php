<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
