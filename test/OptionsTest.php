<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DeveloperTools;

use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\ReportInterface;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testStatusOfDefaultConfiguration()
    {
        $dist = require __DIR__ . '/../config/laminas-developer-tools.local.php.dist';
        $reportMock = $this->prophesize(ReportInterface::class)->reveal();
        $options = new Options($dist['laminas-developer-tools'], $reportMock);
        $this->assertTrue($options->isEnabled());
        $this->assertTrue($options->isToolbarEnabled());
    }
}
