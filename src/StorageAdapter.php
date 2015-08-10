<?php

namespace PhpInPractice\EventStore;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PhpInPractice\EventStore\Storage\Doctrine;

/**
 * Represents a backend for the event store to write data to and from.
 */
interface StorageAdapter
{
    /**
     * Perform a one-time initialization, for example to create a database schema.
     *
     * @param string[] $options
     *
     * @return void
     */
    public function initialize(array $options = []);

    /**
     * Returns all events for the given stream.
     *
     * @param Stream $stream
     *
     * @return Stream\Event[]
     */
    public function fetchEventsForStream(Stream $stream);

    /**
     * Persists all uncommitted events to the given stream.
     *
     * @param Stream\Event[] $uncommittedEvents
     *
     * @return void
     */
    public function persist(array $uncommittedEvents);
}
