<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\EventLogging;

use InvalidArgumentException;
use Laminas\EventManager\EventInterface;

use function array_splice;
use function basename;
use function debug_backtrace;
use function dirname;
use function file_exists;
use function get_resource_type;
use function gettype;
use function is_object;
use function is_resource;
use function is_scalar;
use function sprintf;

/**
 * Class to provide context information for a passed event.
 */
class EventContextProvider implements EventContextInterface
{
    /** @var EventInterface */
    protected $event;

    private array $debugBacktrace = [];

    /**
     * @param EventInterface|null $event (Optional) The event to provide context to.
     * The event must be set either here or with {@see setEvent()} before any other methods can be used.
     */
    public function __construct(?EventInterface $event = null)
    {
        if ($event) {
            $this->setEvent($event);
        }
    }

    /**
     * @see \Laminas\DeveloperTools\EventLogging\EventContextInterface::setEvent()
     *
     * @param  EventInterface $event The event to add context to.
     * @return void
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @see \Laminas\DeveloperTools\EventLogging\EventContextInterface::getEvent()
     *
     * @return EventInterface
     */
    public function getEvent()
    {
        if (! $this->event) {
            throw new InvalidArgumentException(sprintf(
                '%s: expects an event to have been set.',
                __METHOD__
            ));
        }

        return $this->event;
    }

    /**
     * Returns either the class name of the target, or the target string
     *
     * @return string
     */
    public function getEventTarget()
    {
        $event = $this->getEvent();

        return $this->getEventTargetAsString($event->getTarget());
    }

    /**
     * Determines a string label to represent an event target.
     *
     * @return string
     */
    private function getEventTargetAsString(mixed $target)
    {
        if (is_object($target)) {
            return $target::class;
        }

        if (is_resource($target)) {
            return get_resource_type($target);
        }

        if (is_scalar($target)) {
            return (string) $target;
        }

        return gettype($target);
    }

    /**
     * Returns the debug_backtrace() for this object with two levels removed so that array starts where this
     * class method was called.
     *
     * @return array
     */
    private function getDebugBacktrace()
    {
        if (! $this->debugBacktrace) {
            //Remove the levels this method introduces
            $trace                = debug_backtrace();
            $this->debugBacktrace = array_splice($trace, 2);
        }

        return $this->debugBacktrace;
    }

    /**
     * Returns the filename and parent directory of the file from which the event was triggered.
     *
     * @return string
     */
    public function getEventTriggerFile()
    {
        $backtrace = $this->getDebugBacktrace();

        if (! isset($backtrace[4]['file'])) {
            return '';
        }

        if (file_exists($backtrace[4]['file'])) {
            return basename(dirname($backtrace[4]['file'])) . '/' . basename($backtrace[4]['file']);
        }

        return '';
    }

    /**
     * Returns the line number of the file from which the event was triggered.
     *
     * @return integer
     */
    public function getEventTriggerLine()
    {
        $backtrace = $this->getDebugBacktrace();

        if (isset($backtrace[4]['line'])) {
            return $backtrace[4]['line'];
        }

        return '';
    }
}
