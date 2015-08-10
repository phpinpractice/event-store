<?php

namespace PhpInPractice\EventStore;

use Assert\Assertion;

final class Stream
{
    /** @var Stream\Id */
    private $id;

    /** @var Metadata */
    private $metadata;

    /** @var int */
    private $sequence;

    /**
     * Initializes this stream with the given id and metadata.
     *
     * @param Stream\Id     $id
     * @param Metadata|null $metadata
     */
    public function __construct(Stream\Id $id, Metadata $metadata = null)
    {
        $this->id       = $id;
        $this->metadata = $metadata ?: new Metadata();
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
     * Returns the metadata associated with this stream.
     *
     * @return Metadata
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * Increments the sequence number for this stream and returns that.
     *
     * This method is used when persisting events to keep track of the position of an
     * event in the chronology of a stream. This information can be used to make snapshots, to
     * replay events from a specific position and provide for optimistic locking.
     *
     * @return int
     */
    public function incrementHead()
    {
        return ++$this->sequence;
    }

    /**
     * Registers a new index for the current event sequence.
     *
     * After the event stream has been loaded we need to register the last index of an event so that
     * we know from which position to continue counting.
     *
     * @param int $sequence
     *
     * @see incrementHead() for more information on sequencing.
     *
     * @return void
     */
    public function moveHeadTo($sequence)
    {
        Assertion::integer($sequence);

        $this->sequence = $sequence;
    }
}
