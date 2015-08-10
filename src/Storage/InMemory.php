<?php

namespace PhpInPractice\EventStore\Storage;

use PhpInPractice\EventStore\StorageAdapter;
use PhpInPractice\EventStore\Stream;

final class InMemory implements StorageAdapter
{
    /** @var Stream\Event */
    private $events;

    /**
     * @inheritDoc
     */
    public function fetchEventsForStream(Stream $stream)
    {
        $id = (string)$stream->id();

        return isset($this->events[$id]) ? $this->events[$id] : [];
    }

    /**
     * @inheritDoc
     */
    public function persist(array $uncommittedEvents)
    {
        /** @var Stream\Event $event */
        foreach ($uncommittedEvents as $event) {
            $id = (string)$event->stream()->id();
            if (!isset($this->events[$id])) {
                $this->events[$id] = [];
            }

            $this->events[$id][] = $event;
        }
    }
}
