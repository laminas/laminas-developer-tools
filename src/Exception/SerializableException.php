<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Exception;

use Serializable;
use Throwable;

use function array_pop;
use function explode;
use function get_resource_type;
use function implode;
use function is_array;
use function is_bool;
use function is_object;
use function is_resource;
use function serialize;
use function unserialize;

class SerializableException implements Serializable
{
    /**
     * Exception Data
     *
     * @var array
     */
    protected $data;

    /**
     * Saves the exception data in an array.
     *
     * @param Throwable $exception
     */
    public function __construct($exception)
    {
        $previous   = $exception->getPrevious();
        $this->data = [
            'code'     => $exception->getCode(),
            'file'     => $exception->getFile(),
            'line'     => $exception->getLine(),
            'class'    => $exception::class,
            'message'  => $exception->getMessage(),
            'previous' => $previous !== null ? new self($previous) : null,
            'trace'    => $this->filterTrace(
                $exception->getTrace(),
                $exception->getFile(),
                $exception->getLine()
            ),
        ];
    }

    /**
     * @return integer|string
     */
    public function getCode()
    {
        return $this->data['code'];
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->data['file'];
    }

    /**
     * @return integer
     */
    public function getLine()
    {
        return $this->data['line'];
    }

    /**
     * @return array
     */
    public function getTrace()
    {
        return $this->data['trace'];
    }

    /**
     * @return string
     */
    public function getTraceAsString()
    {
        return implode("\n", $this->data['trace']);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->data['message'];
    }

    /**
     * @return self|null
     */
    public function getPrevious()
    {
        return $this->data['previous'];
    }

    /**
     * This function uses code coming from Symfony 2.
     *
     * @param  array   $trace
     * @param  string  $file
     * @param  integer $line
     * @return array
     */
    protected function filterTrace($trace, $file, $line)
    {
        $filteredTrace = [];

        $filteredTrace[] = [
            'namespace'   => '',
            'short_class' => '',
            'class'       => '',
            'type'        => '',
            'function'    => '',
            'file'        => $file,
            'line'        => $line,
            'args'        => [],
        ];

        foreach ($trace as $entry) {
            $class     = '';
            $namespace = '';

            if (isset($entry['class'])) {
                $parts     = explode('\\', $entry['class']);
                $class     = array_pop($parts);
                $namespace = implode('\\', $parts);
            }

            $filteredTrace[] = [
                'namespace'   => $namespace,
                'short_class' => $class,
                'class'       => $entry['class'] ?? '',
                'type'        => $entry['type'] ?? '',
                'function'    => $entry['function'],
                'file'        => $entry['file'] ?? null,
                'line'        => $entry['line'] ?? null,
                'args'        => isset($entry['args']) ? $this->filterArgs($entry['args']) : [],
            ];
        }

        return $filteredTrace;
    }

    /**
     * This function uses code coming from Symfony 2.
     *
     * @param  array   $args
     * @param  integer $level
     * @return array
     */
    protected function filterArgs($args, $level = 0)
    {
        $result = [];

        foreach ($args as $key => $value) {
            if (is_object($value)) {
                $result[$key] = ['object', $value::class];
                continue;
            }

            if (is_array($value)) {
                if ($level > 10) {
                    $result[$key] = ['array', '*DEEP NESTED ARRAY*'];
                    continue;
                }
                $result[$key] = ['array', $this->filterArgs($value, ++$level)];
                continue;
            }

            if (null === $value) {
                $result[$key] = ['null', null];
                continue;
            }

            if (is_bool($value)) {
                $result[$key] = ['boolean', $value];
                continue;
            }

            if (is_resource($value)) {
                $result[$key] = ['resource', get_resource_type($value)];
                continue;
            }

            $result[$key] = ['string', (string) $value];
        }

        return $result;
    }

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
