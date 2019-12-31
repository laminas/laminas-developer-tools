<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

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
