<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools;

use BjyProfiler\Db\Adapter\ProfilingAdapter;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\InitProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\ModuleManager\ModuleManagerInterface;

class Module implements
    InitProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface,
    ViewHelperProviderInterface
{
    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        defined('REQUEST_MICROTIME') || define('REQUEST_MICROTIME', microtime(true));

        if (PHP_SAPI === 'cli') {
            return;
        }

        $eventManager = $manager->getEventManager();
        $eventManager->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            [$this, 'onLoadModulesPost'],
            -1100
        );
    }

    /**
     * loadModulesPost callback
     *
     * @param  $event
     */
    public function onLoadModulesPost($event)
    {
        $eventManager  = $event->getTarget()->getEventManager();
        $configuration = $event->getConfigListener()->getMergedConfig(false);

        if (isset($configuration['laminas-developer-tools']['profiler']['enabled'])
            && $configuration['laminas-developer-tools']['profiler']['enabled'] === true
        ) {
            $eventManager->trigger(ProfilerEvent::EVENT_PROFILER_INIT, $event);
        }
    }

    /**
     * Laminas\Mvc\MvcEvent::EVENT_BOOTSTRAP event callback
     *
     * @param  EventInterface $event
     * @throws Exception\InvalidOptionException
     * @throws Exception\ProfilerException
     */
    public function onBootstrap(EventInterface $event)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        $app = $event->getApplication();
        $sm  = $app->getServiceManager();

        $options = $sm->get('Laminas\DeveloperTools\Config');

        if (! $options->isToolbarEnabled()) {
            return;
        }

        $em  = $app->getEventManager();
        $report = $sm->get(Report::class);

        if ($options->canFlushEarly()) {
            $flushListener = $sm->get('Laminas\DeveloperTools\FlushListener');
            $flushListener->attach($em);
        }

        if ($options->isStrict() && $report->hasErrors()) {
            throw new Exception\InvalidOptionException(implode(' ', $report->getErrors()));
        }

        if ($options->eventCollectionEnabled()) {
            $sem = $em->getSharedManager();
            $eventLoggingListener = $sm->get(Listener\EventLoggingListenerAggregate::class);
            $eventLoggingListener->attachShared($sem);
        }

        $profilerListener = $sm->get(Listener\ProfilerListener::class);
        $profilerListener->attach($em);

        if ($options->isToolbarEnabled()) {
            $toolbarListener = $sm->get(Listener\ToolbarListener::class);
            $toolbarListener->attach($em);
        }

        if ($options->isStrict() && $report->hasErrors()) {
            throw new Exception\ProfilerException(implode(' ', $report->getErrors()));
        }
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return [
            // Legacy Zend Framework aliases
            'aliases' => [
                'ZendDeveloperToolsTime' => 'LaminasDeveloperToolsTime',
                'ZendDeveloperToolsMemory' => 'LaminasDeveloperToolsMemory',
                'ZendDeveloperToolsDetailArray' => 'LaminasDeveloperToolsDetailArray',
            ],
            'invokables' => [
                'LaminasDeveloperToolsTime'        => View\Helper\Time::class,
                'LaminasDeveloperToolsMemory'      => View\Helper\Memory::class,
                'LaminasDeveloperToolsDetailArray' => View\Helper\DetailArray::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getServiceConfig()
    {
        return [
            'aliases' => [
                'Laminas\DeveloperTools\ReportInterface' => Report::class,

                // Legacy Zend Framework aliases
                'ZendDeveloperTools\ReportInterface' => 'Laminas\DeveloperTools\ReportInterface',
                \ZendDeveloperTools\Report::class => Report::class,
                'ZendDeveloperTools\ExceptionCollector' => 'Laminas\DeveloperTools\ExceptionCollector',
                'ZendDeveloperTools\RequestCollector' => 'Laminas\DeveloperTools\RequestCollector',
                'ZendDeveloperTools\ConfigCollector' => 'Laminas\DeveloperTools\ConfigCollector',
                'ZendDeveloperTools\MailCollector' => 'Laminas\DeveloperTools\MailCollector',
                'ZendDeveloperTools\MemoryCollector' => 'Laminas\DeveloperTools\MemoryCollector',
                'ZendDeveloperTools\TimeCollector' => 'Laminas\DeveloperTools\TimeCollector',
                'ZendDeveloperTools\FlushListener' => 'Laminas\DeveloperTools\FlushListener',
                \ZendDeveloperTools\Profiler::class => Profiler::class,
                'ZendDeveloperTools\Config' => 'Laminas\DeveloperTools\Config',
                'ZendDeveloperTools\Event' => 'Laminas\DeveloperTools\Event',
                'ZendDeveloperTools\StorageListener' => 'Laminas\DeveloperTools\StorageListener',
                \ZendDeveloperTools\Listener\ToolbarListener::class => Listener\ToolbarListener::class,
                \ZendDeveloperTools\Listener\ProfilerListener::class => Listener\ProfilerListener::class,
                \ZendDeveloperTools\Listener\EventLoggingListenerAggregate::class => Listener\EventLoggingListenerAggregate::class,
                'ZendDeveloperTools\DbCollector' => 'Laminas\DeveloperTools\DbCollector',
            ],
            'invokables' => [
                Report::class                           => Report::class,
                'Laminas\DeveloperTools\ExceptionCollector' => Collector\ExceptionCollector::class,
                'Laminas\DeveloperTools\RequestCollector'   => Collector\RequestCollector::class,
                'Laminas\DeveloperTools\ConfigCollector'    => Collector\ConfigCollector::class,
                'Laminas\DeveloperTools\MailCollector'      => Collector\MailCollector::class,
                'Laminas\DeveloperTools\MemoryCollector'    => Collector\MemoryCollector::class,
                'Laminas\DeveloperTools\TimeCollector'      => Collector\TimeCollector::class,
                'Laminas\DeveloperTools\FlushListener'      => Listener\FlushListener::class,
            ],
            'factories' => [
                Profiler::class => function ($sm) {
                    $a = new Profiler($sm->get(Report::class));
                    $a->setEvent($sm->get('Laminas\DeveloperTools\Event'));
                    return $a;
                },
                'Laminas\DeveloperTools\Config' => function ($sm) {
                    $config = $sm->get('Configuration');
                    $config = isset($config['laminas-developer-tools']) ? $config['laminas-developer-tools'] : null;

                    return new Options($config, $sm->get(Report::class));
                },
                'Laminas\DeveloperTools\Event' => function ($sm) {
                    $event = new ProfilerEvent();
                    $event->setReport($sm->get(Report::class));
                    $event->setApplication($sm->get('Application'));

                    return $event;
                },
                'Laminas\DeveloperTools\StorageListener' => function ($sm) {
                    return new Listener\StorageListener($sm);
                },
                Listener\ToolbarListener::class => function ($sm) {
                    return new Listener\ToolbarListener(
                        $sm->get('ViewRenderer'),
                        $sm->get('Laminas\DeveloperTools\Config')
                    );
                },
                Listener\ProfilerListener::class => function ($sm) {
                    return new Listener\ProfilerListener($sm, $sm->get('Laminas\DeveloperTools\Config'));
                },
                Listener\EventLoggingListenerAggregate::class => function ($sm) {
                    $config = $sm->get('Laminas\DeveloperTools\Config');

                    return new Listener\EventLoggingListenerAggregate(
                        array_map([$sm, 'get'], $config->getEventCollectors()),
                        $config->getEventIdentifiers()
                    );
                },
                'Laminas\DeveloperTools\DbCollector' => function ($sm) {
                    $p  = false;
                    $db = new Collector\DbCollector();

                    if ($sm->has('Laminas\Db\Adapter\Adapter') && isset($sm->get('config')['db'])) {
                        $adapter = $sm->get('Laminas\Db\Adapter\Adapter');
                        if ($adapter instanceof ProfilingAdapter) {
                            $p = true;
                            $db->setProfiler($adapter->getProfiler());
                        }
                    }

                    if (! $p && $sm->has('Laminas\Db\Adapter\AdapterInterface') && isset($sm->get('config')['db'])) {
                        $adapter = $sm->get('Laminas\Db\Adapter\AdapterInterface');
                        if ($adapter instanceof ProfilingAdapter) {
                            $p = true;
                            $db->setProfiler($adapter->getProfiler());
                        }
                    }

                    if (! $p && $sm->has('Laminas\Db\Adapter\ProfilingAdapter')) {
                        $adapter = $sm->get('Laminas\Db\Adapter\ProfilingAdapter');
                        if ($adapter instanceof ProfilingAdapter) {
                            $db->setProfiler($adapter->getProfiler());
                        }
                    }

                    return $db;
                },
            ],
        ];
    }
}
