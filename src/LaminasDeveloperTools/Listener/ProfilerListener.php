<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Profiler Listener
 *
 * Listens to the MvcEvent::EVENT_FINISH event and starts collecting data.
 *
 */
class ProfilerListener implements ListenerAggregateInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Options
     */
    protected $options;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * Constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param Options                 $options
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Options $options)
    {
        $this->options        = $options;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritdoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_FINISH,
            array($this, 'onFinish'),
            Profiler::PRIORITY_PROFILER
        );
    }

    /**
     * @inheritdoc
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * MvcEvent::EVENT_FINISH event callback
     *
     * @param  MvcEvent $event
     * @throws ServiceNotFoundException
     */
    public function onFinish(MvcEvent $event)
    {
        $strict     = $this->options->isStrict();
        $collectors = $this->options->getCollectors();
        $report     = $this->serviceLocator->get('Laminas\DeveloperTools\Report');
        $profiler   = $this->serviceLocator->get('Laminas\DeveloperTools\Profiler');

        $profiler->setErrorMode($strict);

        foreach ($collectors as $name => $collector) {
            if ($this->serviceLocator->has($collector)) {
                $profiler->addCollector($this->serviceLocator->get($collector));
            } else {
                $error = sprintf('Unable to fetch or create an instance for %s.', $collector);
                if ($strict === true) {
                    throw new ServiceNotFoundException($error);
                } else {
                    $report->addError($error);
                }
            }
        }

        $profiler->collect($event);
    }
}
