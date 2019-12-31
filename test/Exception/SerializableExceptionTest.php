<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DeveloperTools\Exception;

use Exception;
use Laminas\DeveloperTools\Exception\SerializableException;
use PHPUnit_Framework_TestCase as TestCase;
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
