<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\DeveloperTools\Report;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function sprintf;

/**
 * Profiler Listener
 *
 * Listens to the MvcEvent::EVENT_FINISH event and starts collecting data.
 */
class ProfilerListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var Options */
    protected $options;

    public function __construct(ServiceLocatorInterface $serviceLocator, Options $options)
    {
        $this->options        = $options;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_FINISH,
            [$this, 'onFinish'],
            Profiler::PRIORITY_PROFILER
        );
    }

    /**
     * MvcEvent::EVENT_FINISH event callback
     *
     * @throws ServiceNotFoundException
     */
    public function onFinish(MvcEvent $event)
    {
        $strict     = $this->options->isStrict();
        $collectors = $this->options->getCollectors();
        $report     = $this->serviceLocator->get(Report::class);
        $profiler   = $this->serviceLocator->get(Profiler::class);

        $profiler->setErrorMode($strict);

        foreach ($collectors as $name => $collector) {
            if ($this->serviceLocator->has($collector)) {
                $profiler->addCollector($this->serviceLocator->get($collector));
                continue;
            }

            $error = sprintf('Unable to fetch or create an instance for %s.', $collector);
            if ($strict === true) {
                throw new ServiceNotFoundException($error);
            }
            $report->addError($error);
        }

        $profiler->collect($event);
    }
}
