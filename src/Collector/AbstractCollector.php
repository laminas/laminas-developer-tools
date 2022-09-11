<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use Serializable;

use function serialize;
use function unserialize;

/**
 * Serializable Collector base class.
 */
abstract class AbstractCollector implements CollectorInterface, Serializable
{
    /**
     * Collected Data
     *
     * @var array
     */
    protected $data;

    /**
     * @return string
     */
    public function __serialize()
    {
        return serialize($this->data);
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
     * @param string $data
     * @return void
     */
    public function __unserialize($data)
    {
        $this->data = unserialize($data);
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function unserialize($data)
    {
        $this->__unserialize($data);
    }
}
