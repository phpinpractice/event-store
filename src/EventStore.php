<?php

namespace PhpInPractice\EventStore;

/**
 * An Event Store implementation that passes events to and from a storage adapter and links events to streams.
 */
final class EventStore implements EventStorage
{
    /** @var StorageAdapter */
    private $storage;

    /**
     * Initialize this event store with a storage adapter to read and write events to and from.
     *
     * @param StorageAdapter $storage
     */
    public function __construct(StorageAdapter $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns all events in order or sequence for a given stream.
     *
     * @param Stream $stream
     *
     * @return Stream\Event[]
     */
    public function fetchEvents(Stream $stream)
    {
        return $this->storage->fetchEventsForStream($stream);
    }

    /**
     * Add the given events to the stream and assign a sequence number to them.
     *
     * @param Stream         $stream
     * @param Stream\Event[] $uncommittedEvents
     *
     * @return void
     */
    public function persist(Stream $stream, array $uncommittedEvents)
    {
        foreach ($uncommittedEvents as &$event) {
            $event = $event->withStream($stream);
        }

        $this->storage->persist($uncommittedEvents);
    }
}
