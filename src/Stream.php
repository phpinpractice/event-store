<?php

namespace PhpInPractice\EventStore;

use Assert\Assertion;

final class Stream
{
    /** @var Stream\Id */
    private $id;

    /** @var int */
    private $head;

    /**
     * Initializes this stream with the given id and metadata.
     *
     * @param Stream\Id $id
     */
    public function __construct(Stream\Id $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the identifier for this stream.
     *
     * @return Stream\Id
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Increments the head position number for this stream and returns that.
     *
     * This method is used when persisting events to keep track of the position of an
     * event in the chronology of a stream. This information can be used to make snapshots, to
     * replay events from a specific position and provide for optimistic locking.
     *
     * @return int
     */
    public function incrementHead()
    {
        return ++$this->head;
    }

    /**
     * Registers a new head position number for the current event sequence.
     *
     * After the event stream has been loaded we need to register the last index of an event so that
     * we know from which position to continue counting.
     *
     * @param int $head
     *
     * @see incrementHead() for more information on sequencing.
     *
     * @return void
     */
    public function moveHeadTo($head)
    {
        Assertion::integer($head);

        $this->head = $head;
    }
}
