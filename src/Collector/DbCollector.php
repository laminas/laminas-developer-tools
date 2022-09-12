<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use BjyProfiler\Db\Profiler\Profiler;
use Laminas\Mvc\MvcEvent;
use Serializable;

use function count;
use function serialize;
use function unserialize;

/**
 * Database (Laminas\Db) Data Collector.
 */
class DbCollector implements CollectorInterface, AutoHideInterface, Serializable
{
    /** @var Profiler */
    protected $profiler;

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'db';
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @inheritDoc
     */
    public function collect(MvcEvent $mvcEvent)
    {
    }

    /**
     * @inheritDoc
     */
    public function canHide()
    {
        if (! isset($this->profiler)) {
            return false;
        }

        if ($this->getQueryCount() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Has the collector access to Bjy's Db Profiler?
     *
     * @return bool
     */
    public function hasProfiler()
    {
        return isset($this->profiler);
    }

    /**
     * Returns Bjy's Db Profiler
     *
     * @return Profiler
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * Sets Bjy's Db Profiler
     *
     * @return self
     */
    public function setProfiler(Profiler $profiler)
    {
        $this->profiler = $profiler;
        return $this;
    }

    /**
     * Returns the number of queries send to the database.
     *
     * You can use the constants in the Profiler class to specify
     * what kind of queries you want to get, e.g. Profiler::INSERT.
     *
     * @param  integer|null $mode
     * @return self
     */
    public function getQueryCount($mode = null)
    {
        return count($this->profiler->getQueryProfiles($mode));
    }

    /**
     * Returns the total time the queries took to execute.
     *
     * You can use the constants in the Profiler class to specify
     * what kind of queries you want to get, e.g. Profiler::INSERT.
     *
     * @param  integer|null $mode
     * @return float|integer
     */
    public function getQueryTime($mode = null)
    {
        $time = 0;

        foreach ($this->profiler->getQueryProfiles($mode) as $query) {
            $time += $query->getElapsedTime();
        }

        return $time;
    }

    /**
     * @return string
     */
    public function __serialize()
    {
        return serialize($this->profiler);
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function serialize()
    {
        return $this->__serialize();
    }

    /**
     * @param string $profiler
     * @return void
     */
    public function __unserialize($profiler)
    {
        $this->profiler = unserialize($profiler);
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function unserialize($profiler)
    {
        $this->__unserialize($profiler);
    }
}
