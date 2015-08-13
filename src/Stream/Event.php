<?php

namespace PhpInPractice\EventStore\Stream;

use PhpInPractice\EventStore\Stream\Event\Metadata;
use PhpInPractice\EventStore\Stream;

/**
 * Immutable entity representing a single event from the event stream.
 *
 * When an instance of this class is created it is not associated with an Event Stream yet and some information may
 * not be provided yet, such as the sequence number and stream. Using the `withStream` method it is possible to clone
 * this event and associate it with a stream, which also gives it a sequence number and updates the sequence number
 * of the stream.
 *
 * As a user you generally need not worry about the above; the withStream method is called by the EventStore once the
 * event is persisted with an active stream.
 */
final class Event
{
    /** @var Event\Id */
    private $id;

    /** @var Stream */
    private $stream;

    /** @var Metadata  */
    private $metadata;

    /** @var \DateTimeInterface */
    private $emittedAt;

    /** @var Event\Payload */
    private $payload;

    /** @var integer|null */
    private $sequence = null;

    /**
     * Initialize a new event with an identifier, payload and optionally the date at which it was originally emitted
     * and some metadata.
     *
     * If the date is omitted than the event assumes it is emitted now or within a few moments and thus sets the
     * emission date to now.
     *
     * @param Event\Id                $id
     * @param Event\Payload           $payload
     * @param \DateTimeInterface|null $emittedAt
     * @param Metadata|null           $metadata
     */
    public function __construct(
        Event\Id $id,
        Event\Payload $payload,
        \DateTimeInterface $emittedAt = null,
        Metadata $metadata = null
    ) {
        $emittedAt = $emittedAt ?: new \DateTimeImmutable();

        $this->id        = $id;
        $this->payload   = $payload;
        $this->emittedAt = $emittedAt->setTimezone(new \DateTimeZone('UTC'));
        $this->metadata  = $metadata ?: new Metadata();
    }

    /**
     * Returns the identifier for this event.
     * @return Event\Id
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Returns the stream to which this event belongs or null if it isn't emitted yet.
     *
     * @return Stream|null
     */
    public function stream()
    {
        return $this->stream;
    }

    /**
     * The data that belongs to this event.
     *
     * Important: the payload object itself is not immutable. During design it was considered whether this should
     * be immutable or not but at this stage I have left it mutable on purpose. This may however change so it is not
     * recommended to rely on this while this notice is here.
     *
     * @return Event\Payload
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * Returns the date at which this event was emitted.
     *
     * @return \DateTimeInterface
     */
    public function emittedAt()
    {
        return $this->emittedAt;
    }

    /**
     * Returns any meta-data that may be relevant for this event.
     *
     * Important: the metadata object itself is not immutable. During design it was considered whether this should
     * be immutable or not but at this stage I have left it mutable on purpose. This may however change so it is not
     * recommended to rely on this while this notice is here.
     *
     * @return Metadata
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * Returns the position of this event in the event stream.
     *
     * The event stream maintains the positions of all events in the stream so that a stream may be snapshotted and
     * rebuilt later from that point onwards, and optimistic locking may be implemented.
     *
     * The position indicators range from 1 to infinity where 1 is the first event and each event thereafter should
     * follow in time. The sequence should never be broken so if a number is missing in the sequence than that event
     * stream is compromised and should be inspected to see if something is wrong.
     *
     * If this event is not yet emitted then it will not yet have a sequence number and this method will return null.
     *
     * @return int|null
     */
    public function sequence()
    {
        return $this->sequence;
    }

    /**
     * Associates a clone of this event with the given stream and assigns it a new sequence number.
     *
     * @param Stream $stream
     *
     * @return Event
     */
    public function withStream(Stream $stream)
    {
        $clone = clone $this;
        $clone->stream   = $stream;
        $clone->sequence = $stream->incrementHead();

        return $clone;
    }

    /**
     * Creates a new event and populates it with the given data.
     *
     * @param Stream[]|string[] $data An array containing the following keys:
     *
     *     - id, the UUID for this new event
     *     - payload, an array containing the data
     *     - emitted_at, a string representation of the (UTC) date  and time at which the event was emitted, it is
     *         recommended to use the MySQL DateTime format (Y-m-d H:i:s) to be certain that the right moment is
     *         captured.
     *     - metadata, an array containing metadata associated with the event
     *     - sequence, the position in the stream of this event
     *     - stream, a Stream object to which this event belongs
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        $event = new static(
            Event\Id::fromString($data['id']),
            new Stream\Event\Payload($data['payload']),
            new \DateTimeImmutable($data['emitted_at']),
            new Metadata($data['metadata'])
        );
        $event->sequence = (int)$data['sequence'];
        $event->stream   = $data['stream'];

        return $event;
    }
}
