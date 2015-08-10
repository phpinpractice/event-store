<?php

namespace PhpInPractice\EventStore;

interface EventStorage
{
    /**
     * Returns all events for the given stream.
     *
     * @param Stream $stream
     *
     * @return Stream\Event[]
     */
    public function fetchEvents(Stream $stream);

    /**
     * Persist the given events to the event stream.
     *
     * @param Stream         $stream
     * @param Stream\Event[] $uncommittedEvents
     *
     * @return void
     */
    public function persist(Stream $stream, array $uncommittedEvents);
}
