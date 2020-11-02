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
use Laminas\View\Renderer\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Laminas\Mvc\Application;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\Headers;
use Laminas\ModuleManager\ModuleManager;

class ToolbarListenerTest extends TestCase
{
    public function testOnCollected()
    {
        $viewRenderer = $this->createMock(PhpRenderer::class);
        $profilerEvent = $this->createMock(ProfilerEvent::class);
        $application = $this->createMock(Application::class);
        $request = $this->createMock(Request::class);
        $application->expects($this->once())
                    ->method('getRequest')
                    ->willReturn($request);

        $response = $this->createMock(Response::class);
        $headers = $this->createMock(Headers::class);
        $response->expects($this->once())
                 ->method('getHeaders')
                 ->willReturn($headers);
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
    }
}
