<?php

namespace LaminasTest\DeveloperTools\Exception;

use Exception;
use Laminas\DeveloperTools\Exception\SerializableException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

class SerializableExceptionTest extends TestCase
{
    public function testSerializableExceptionUsesPreviousExceptionMessage()
    {
        $original = new Exception('foo');
        $serializable = new SerializableException($original);
        $this->assertEquals($original->getMessage(), $serializable->getMessage());
    }

    /**
     * @requires PHP 7
     */
    public function testSerializableExceptionReportsCallToUndefinedMethod()
    {
        try {
            (new stdClass)->iDoNotExist();
        } catch (Throwable $exception) {
            $serializable = new SerializableException($exception);
            $this->assertEquals('Call to undefined method stdClass::iDoNotExist()', $serializable->getMessage());
        }
    }
}
