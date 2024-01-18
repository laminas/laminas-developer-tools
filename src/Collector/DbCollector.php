<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use BjyProfiler\Db\Profiler\Profiler;
use Laminas\Mvc\MvcEvent;
use Serializable;

use function array_key_exists;
use function assert;
use function count;
use function is_array;
use function serialize;
use function unserialize;

/**
 * Database (Laminas\Db) Data Collector.
 */
class DbCollector implements CollectorInterface, AutoHideInterface, Serializable
{
    /** @var null|Profiler */
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
     * @return null|Profiler
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
     * @return null|integer
     */
    public function getQueryCount($mode = null)
    {
        if ($this->profiler === null) {
            return null;
        }
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
     * @return array
     */
    public function __serialize()
    {
        return ['profiler' => $this->profiler];
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->__serialize());
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize($data)
    {
        assert(array_key_exists('profiler', $data));
        assert($data['profiler'] === null || $data['profiler'] instanceof Profiler);
        $this->profiler = $data['profiler'];
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        assert(is_array($data));
        $this->__unserialize($data);
    }
}
