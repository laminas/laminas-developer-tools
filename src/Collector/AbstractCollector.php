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
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
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
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     */
    public function unserialize($data)
    {
        $this->__unserialize($data);
    }
}
