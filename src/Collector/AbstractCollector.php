<?php

namespace Laminas\DeveloperTools\Collector;

/**
 * Serializable Collector base class.
 *
 */
abstract class AbstractCollector implements CollectorInterface, \Serializable
{
    /**
     * Collected Data
     *
     * @var array
     */
    protected $data;

    /**
     * @see \Serializable
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * @see \Serializable
     */
    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
