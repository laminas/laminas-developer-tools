<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Collector;

use Closure;
use Laminas\DeveloperTools\Stub\ClosureStub;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\ArrayUtils;
use Serializable;
use Traversable;

/**
 * Config data collector - dumps the contents of the `Config` and `ApplicationConfig` services
 */
class ConfigCollector implements CollectorInterface, Serializable
{
    const NAME     = 'config';
    const PRIORITY = 100;

    /**
     * @var array|null
     */
    protected $config;

    /**
     * @var array|null
     */
    protected $applicationConfig;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return static::PRIORITY;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(['config' => $this->config, 'applicationConfig' => $this->applicationConfig]);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data                    = unserialize($serialized);
        $this->config            = $data['config'];
        $this->applicationConfig = $data['applicationConfig'];
    }

    /**
     * Replaces the un-serializable items in an array with stubs
     *
     * @param array|\Traversable $data
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
                $unserialized[$key] = function () {
                };
                continue;
            }

            $unserialized[$key] = $value;
        }

        return $unserialized;
    }
}
