<?php

namespace PhpInPractice\EventStore;

final class EventStore implements EventStorage
{
    /** @var StorageAdapter */
    private $storage;

    public function __construct(StorageAdapter $storage)
    {
        $this->storage = $storage;
    }

    public function fetchEvents(Stream $stream)
    {
        return $this->storage->fetchEventsForStream($stream);
    }

    /**
     * @param Stream         $stream
     * @param Stream\Event[] $uncommittedEvents
     */
    public function persist(Stream $stream, array $uncommittedEvents)
    {
        foreach ($uncommittedEvents as &$event) {
            $event = $event->withStream($stream);
        }

        $this->storage->persist($uncommittedEvents);
    }
}
