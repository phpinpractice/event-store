<?php

namespace PhpInPractice\EventStore\Stream\Event;

/**
 * A Collection object that represents the payload of an event.
 *
 * Stream Events will record any data that they should register
 * and remember. As such they may be used as a sort of envelope
 * around domain events; the domain event is transformed into
 * an array representing and that is used as payload for a stream
 * event.
 */
final class Payload extends \ArrayObject
{

}
