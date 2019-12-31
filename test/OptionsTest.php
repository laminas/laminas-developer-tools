<?php
namespace LaminasTest\DeveloperTools;

use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\ReportInterface;
use PHPUnit_Framework_TestCase;

class OptionsTest extends PHPUnit_Framework_TestCase
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
