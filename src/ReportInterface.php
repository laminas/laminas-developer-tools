<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools;

use DateTime;

interface ReportInterface
{
    /**
     * @param  string $ip
     * @return self
     */
    public function setIp($ip);

    /**
     * @return string
     */
    public function getIp();

    /**
     * @param  string $uri
     * @return self
     */
    public function setUri($uri);

    /**
     * @return string
     */
    public function getUri();

    /**
     * @param DateTime $time
     * @return self
     */
    public function setTime($time);

    /**
     * @return DateTime
     */
    public function getTime();

    /**
     * @param  string $token
     * @return self
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param  string $error
     * @return self
     */
    public function addError($error);

    /**
     * @return array|null
     */
    public function getErrors();

    /**
     * @return bool
     */
    public function hasErrors();

    /**
     * @param  string $method
     * @return self
     */
    public function setMethod($method);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param  string $name
     * @return bool
     */
    public function hasCollector($name);

    /**
     * @param  string $name
     * @return Collector\CollectorInterface|null
     */
    public function getCollector($name);

    /**
     * @return array
     */
    public function getCollectors();

    /**
     * @return array
     */
    public function getCollectorNames();

    /**
     * @param  array $collectors
     * @return self
     */
    public function setCollectors(array $collectors);

    /**
     * @return self
     */
    public function addCollector(Collector\CollectorInterface $collector);
}
