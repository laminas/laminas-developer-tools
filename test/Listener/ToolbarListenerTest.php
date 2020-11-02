<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DeveloperTools\Listener;

use Laminas\DeveloperTools\Listener\ToolbarListener;
use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\ProfilerEvent;
use Laminas\DeveloperTools\Report;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\Application;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Renderer\PhpRenderer;
use PHPUnit\Framework\TestCase;

class ToolbarListenerTest extends TestCase
{
    public function provideHTMLBody()
    {
        return [
            'not HTML5 has head and body' => ['<html><head></head><body></body></html>', true],
            'not HTML5 has body' => ['<html><body></body></html>', true],
            'not HTML5 not has body' => ['<html></html>', false],
            'HTML5 with head' => ['<!doctype html><head></head><body></body>', true],
            'HTML5 without head' => ['<!doctype html><body></body>', true],
            'HTML5 without body' => ['<!doctype html>test', true],
        ];
    }

    /**
     * @dataProvider provideHTMLBody
     */
    public function testOnCollected($htmlBody, $isInjected)
    {
        $viewRenderer = $this->createMock(PhpRenderer::class);
        $viewRenderer->expects($this->any())
                     ->method('render')
                     ->willReturn('script');

        $profilerEvent = $this->createMock(ProfilerEvent::class);
        $application = $this->createMock(Application::class);
        $request = $this->createMock(Request::class);
        $application->expects($this->once())
                    ->method('getRequest')
                    ->willReturn($request);

        $response = new Response();
        $response->setContent($htmlBody);
        $application->expects($this->any())
                    ->method('getResponse')
                    ->willReturn($response);

        $serviceManager = $this->createMock(ServiceManager::class);
        $application->expects($this->any())
            ->method('getServiceManager')
            ->willReturn($serviceManager);
        $moduleManager = $this->createMock(ModuleManager::class);
        $moduleManager->expects($this->once())
                        ->method('getLoadedModules')
                        ->willReturn([]);

        $serviceManager->expects($this->once())
                       ->method('get')
                       ->with('ModuleManager')
                       ->willReturn($moduleManager);

        $profilerEvent
            ->expects($this->any())
            ->method('getApplication')
            ->willReturn($application);

        $profilerEvent
            ->expects($this->once())
            ->method('getReport')
            ->willReturn(new Report());

        $option = $this->createMock(Options::class);
        $option->expects($this->once())
               ->method('getToolbarEntries')
               ->willReturn([]);

        $listener = new ToolbarListener($viewRenderer, $option);
        $listener->onCollected($profilerEvent);

        if ($isInjected) {
            $this->assertRegExp('/script/', $response->getBody());
        }
    }
}
