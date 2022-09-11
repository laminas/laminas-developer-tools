<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools;

use DateTime;

use function array_keys;

class Report implements ReportInterface
{
    /** @var string */
    protected $ip;

    /** @var string */
    protected $uri;

    /** @var DateTime */
    protected $time;

    /** @var string */
    protected $token;

    /** @var array */
    protected $errors;

    /** @var string */
    protected $method;

    /** @var array */
    protected $collectors = [];

    /**
     * @inheritDoc
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @inheritDoc
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @inheritDoc
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @inheritDoc
     */
    public function addError($error)
    {
        if (! isset($this->errors)) {
            $this->errors = [];
        }

        $this->errors[] = $error;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
     * @inheritDoc
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function hasCollector($name)
    {
        return isset($this->collectors[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getCollector($name)
    {
        if (isset($this->collectors[$name])) {
            return $this->collectors[$name];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCollectors()
    {
        return $this->collectors;
    }

    /**
     * @inheritDoc
     */
    public function getCollectorNames()
    {
        return array_keys($this->collectors);
    }

    /**
     * @inheritDoc
     */
    public function setCollectors(array $collectors)
    {
        foreach ($collectors as $collector) {
            $this->addCollector($collector);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addCollector(Collector\CollectorInterface $collector)
    {
        $this->collectors[$collector->getName()] = $collector;

        return $this;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['ip', 'uri', 'time', 'token', 'errors', 'method', 'collectors'];
    }
}
