<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use Closure;
use Laminas\DeveloperTools\Stub\ClosureStub;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ArrayUtils;
use Serializable;
use Traversable;

use function array_key_exists;
use function assert;
use function is_array;
use function serialize;
use function unserialize;

/**
 * Config data collector - dumps the contents of the `Config` and `ApplicationConfig` services
 */
class ConfigCollector implements CollectorInterface, Serializable
{
    public const NAME     = 'config';
    public const PRIORITY = 100;

    /** @var array|null */
    protected $config;

    /** @var array|null */
    protected $applicationConfig;

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return static::PRIORITY;
    }

    /**
     * @inheritDoc
     */
    public function collect(MvcEvent $mvcEvent)
    {
        if (! $application = $mvcEvent->getApplication()) {
            return;
        }

        $serviceLocator = $application->getServiceManager();

        if ($serviceLocator->has('config')) {
            $this->config = $this->makeArraySerializable($serviceLocator->get('config'));
        }

        if ($serviceLocator->has('ApplicationConfig')) {
            $this->applicationConfig = $this->makeArraySerializable($serviceLocator->get('ApplicationConfig'));
        }
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return isset($this->config) ? $this->unserializeArray($this->config) : null;
    }

    /**
     * @return array|null
     */
    public function getApplicationConfig()
    {
        return isset($this->applicationConfig) ? $this->unserializeArray($this->applicationConfig) : null;
    }

    /**
     * @return array
     */
    public function __serialize()
    {
        return ['config' => $this->config, 'applicationConfig' => $this->applicationConfig];
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
        assert(array_key_exists('config', $data));
        assert($data['config'] === null || is_array($data['config']));
        assert(array_key_exists('applicationConfig', $data));
        assert($data['applicationConfig'] === null || is_array($data['applicationConfig']));
        $this->config            = $data['config'];
        $this->applicationConfig = $data['applicationConfig'];
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

    /**
     * Replaces the un-serializable items in an array with stubs
     *
     * @param array|Traversable $data
     * @return array
     */
    private function makeArraySerializable($data)
    {
        $serializable = [];

        foreach (ArrayUtils::iteratorToArray($data) as $key => $value) {
            if ($value instanceof Traversable || is_array($value)) {
                $serializable[$key] = $this->makeArraySerializable($value);
                continue;
            }

            if ($value instanceof Closure) {
                $serializable[$key] = new ClosureStub();
                continue;
            }

            $serializable[$key] = $value;
        }

        return $serializable;
    }

    /**
     * Opposite of {@see makeArraySerializable} - replaces stubs in an array with actual un-serializable objects
     *
     * @param array $data
     * @return array
     */
    private function unserializeArray(array $data)
    {
        $unserialized = [];

        foreach (ArrayUtils::iteratorToArray($data) as $key => $value) {
            if ($value instanceof Traversable || is_array($value)) {
                $unserialized[$key] = $this->unserializeArray($value);
                continue;
            }

            if ($value instanceof ClosureStub) {
                $unserialized[$key] = static function (): void {
                };
                continue;
            }

            $unserialized[$key] = $value;
        }

        return $unserialized;
    }
}
