<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools;

use Laminas\EventManager\Event;
use Laminas\Mvc\ApplicationInterface;

/** @template-extends Event<null, ApplicationInterface|Profiler|ReportInterface> */
class ProfilerEvent extends Event
{
    /**
     * The EVENT_PROFILER_INIT occurs on bootstrap if the profiler is enabled.
     *
     * This event allows you to grab the report.
     *
     * @var string
     */
    public const EVENT_PROFILER_INIT = 'profiler_init';

    /**
     * The EVENT_COLLECTED occurs after all data was collected.
     *
     * This event allows you to grab the report.
     *
     * @var string
     */
    public const EVENT_COLLECTED = 'collected';

    /**
     * Set Application
     *
     * @return ApplicationInterface|null
     */
    public function getApplication()
    {
        return $this->getParam('application');
    }

    /**
     * Set Application
     *
     * @return self
     */
    public function setApplication(ApplicationInterface $application)
    {
        $this->setParam('application', $application);
        return $this;
    }

    /**
     * Get profiler
     *
     * @return string
     */
    public function getProfiler()
    {
        return $this->getParam('profiler');
    }

    /**
     * Set profiler
     *
     * @return self
     */
    public function setProfiler(Profiler $profiler)
    {
        $this->setParam('profiler', $profiler);
        return $this;
    }

    /**
     * Get report
     *
     * @return ReportInterface
     */
    public function getReport()
    {
        return $this->getParam('report');
    }

    /**
     * Set report
     *
     * @return self
     */
    public function setReport(ReportInterface $report)
    {
        $this->setParam('report', $report);
        return $this;
    }
}
