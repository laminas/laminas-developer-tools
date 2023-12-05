<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use Serializable;

use function assert;
use function is_array;
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
     * @return array
     */
    public function __serialize()
    {
        return ['data' => $this->data];
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
        assert(isset($data['data']) && is_array($data['data']));
        $this->data = $data['data'];
    }

    /**
     * @deprecated since 2.3.0, this method will be removed in version 3.0.0 of this component.
     *             {@see Serializable} as alternative
     *
     * @inheritDoc
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        assert(is_array($data));
        $this->__unserialize($data);
    }
}
