<?php

namespace Laminas\DeveloperTools\Collector;

use Serializable;

/**
 * Serializable Collector base class.
 *
 */
abstract class AbstractCollector implements CollectorInterface, Serializable
{
    /**
     * Collected Data
     *
     * @var array
     */
    protected $data;

    public function __serialize()
    {
        return serialize($this->data);
    }

    /**
     * @see Serializable
     * @deprecated
     */
    public function serialize()
    {
        return $this->__serialize();
    }

    public function __unserialize($data)
    {
        $this->data = unserialize($data);
    }

    /**
     * @see Serializable
     * @deprecated
     */
    public function unserialize($data)
    {
        $this->__unserialize($data);
    }
}
