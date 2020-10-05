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
        /** @var ReportInterface $reportMock */
        $reportMock = $this->createMock(ReportInterface::class);
        $options = new Options($dist['laminas-developer-tools'], $reportMock);
        $this->assertTrue($options->isEnabled());
        $this->assertTrue($options->isToolbarEnabled());
    }

    public function blacklistFlags()
    {
        yield 'null' => [null];
        yield 'false' => [false];
    }

    /**
     * @see https://framework.zend.com/security/advisory/ZF2019-01
     * @dataProvider blacklistFlags
     * @param null|bool $flagValue
     */
    public function testOnlyWhitelistedToolbarEntriesShouldBeEnabled(?bool $flagValue)
    {
        $reportMock     = $this->createMock(ReportInterface::class);
        /** @var ReportInterface $reportMock */
        $options        = new Options([], $reportMock);
        $toolbarOptions = [
            'enabled' => true,
            'entries' => [
                'request' => $flagValue,
                'time'    => true,
                'config'  => $flagValue,
            ],
        ];

        $options->setToolbar($toolbarOptions);

        $this->assertTrue($options->isToolbarEnabled());

        $entries = $options->getToolbarEntries();
        $this->assertArrayNotHasKey(
            'request',
            $entries,
            'Request key found in toolbar entries, and should not have been'
        );
        $this->assertArrayHasKey(
            'time',
            $entries,
            'Time key NOT found in toolbar entries, and should have been'
        );
        $this->assertArrayNotHasKey(
            'config',
            $entries,
            'Config key found in toolbar entries, and should not have been'
        );
    }
}
