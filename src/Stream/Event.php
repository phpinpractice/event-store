<?php

namespace PhpInPractice\EventStore\Stream;

use PhpInPractice\EventStore\Metadata;
use PhpInPractice\EventStore\Stream;

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

    public function id()
    {
        return $this->id;
    }

    public function stream()
    {
        return $this->stream;
    }

    public function payload()
    {
        return $this->payload;
    }

    public function emittedAt()
    {
        return $this->emittedAt;
    }

    public function metadata()
    {
        return $this->metadata;
    }

    public function sequence()
    {
        return $this->sequence;
    }

    public function withStream(Stream $stream)
    {
        $clone = clone $this;
        $clone->stream   = $stream;
        $clone->sequence = $stream->incrementHead();

        return $clone;
    }

    public static function fromArray(array $data)
    {
        $event = new static(
            Event\Id::fromString($data['id']),
            new Stream\Event\Payload($data['payload']),
            new \DateTimeImmutable($data['emitted_at']),
            new Metadata($data['metadata'])
        );
        $event->sequence = $data['sequence'];
        $event->stream   = $data['stream'];

        return $event;
    }
}
