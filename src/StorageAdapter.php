<?php

namespace PhpInPractice\EventStore;

/**
 * Represents a backend for the event store to write data to and from.
 */
interface StorageAdapter
{
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
