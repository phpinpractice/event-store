<?php

namespace PhpInPractice\EventStore\Stream;

use PhpInPractice\EventStore\Stream\Event\Metadata;
use PhpInPractice\EventStore\Stream;
use Rhumsaa\Uuid\Uuid;

/**
 * @coversDefaultClass PhpInPractice\EventStore\Stream\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::id
     */
    public function it_has_an_identifier()
    {
        $id    = Event\id::generate();
        $event = new Event($id, new Event\Payload());

        $this->assertSame($id, $event->id());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::payload
     */
    public function it_has_a_payload()
    {
        $payload = new Event\Payload();
        $event = new Event(Event\id::generate(), $payload);

        $this->assertSame($payload, $event->payload());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::emittedAt
     */
    public function it_has_the_creation_date_and_time_by_default()
    {
        $event = new Event(Event\id::generate(), new Event\Payload());

        $this->assertInstanceOf(\DateTimeImmutable::class, $event->emittedAt());

        // since a current time is prone to race conditions we check if the generated timestamp falls between
        // 2 seconds before now and 2 seconds after now; which is good enough
        $this->assertGreaterThanOrEqual(time() - 2, $event->emittedAt()->getTimestamp());
        $this->assertLessThanOrEqual(time() + 2, $event->emittedAt()->getTimestamp());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::emittedAt
     */
    public function creation_date_and_time_can_be_overridden()
    {
        $dateTime = new \DateTimeImmutable('2000-01-01 00:00');
        $event = new Event(Event\id::generate(), new Event\Payload(), $dateTime);

        $this->assertInstanceOf(\DateTimeImmutable::class, $event->emittedAt());
        $this->assertSame($dateTime->getTimestamp(), $event->emittedAt()->getTimestamp());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::metadata
     */
    public function it_can_have_meta_data_with_it()
    {
        $metadata = new Metadata(['testKey' => 'testData']);
        $event = new Event(Event\id::generate(), new Event\Payload(), null, $metadata);

        $this->assertSame($metadata, $event->metadata());
        $this->assertSame($metadata['testKey'], 'testData');
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::sequence
     * @covers ::stream
     */
    public function it_is_not_associated_with_a_stream_by_default_and_has_no_sequence()
    {
        $event = new Event(Event\id::generate(), new Event\Payload());
        $this->assertNull($event->sequence());
        $this->assertNull($event->stream());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::withStream
     * @covers ::sequence
     * @covers ::stream
     */
    public function associating_it_with_a_stream_creates_a_new_event_with_sequence_number()
    {
        $event = new Event(Event\Id::generate(), new Event\Payload());
        $stream = new Stream(Id::generate());

        // test if HEAD is tracked by incrementing HEAD a few times.
        $stream->incrementHead();
        $stream->incrementHead();

        $this->assertAttributeSame(2, 'sequence', $stream);

        $result = $event->withStream($stream);

        $this->assertNotSame($event, $result);
        $this->assertInstanceOf(Event::class, $result);
        $this->assertNull($event->stream());
        $this->assertSame($stream, $result->stream());
        $this->assertSame(3, $result->sequence());
        $this->assertSame(null, $event->sequence());
        $this->assertAttributeSame(3, 'sequence', $stream);
    }

    /**
     * @test
     * @covers ::fromArray
     */
    public function it_can_be_reconstituted_from_array()
    {
        $uuid  = Uuid::uuid4();
        $payload = ['payload'];
        $stream = new Stream(Id::generate());

        $event = Event::fromArray([
            'id'         => $uuid,
            'payload'    => $payload,
            'emitted_at' => '2000-01-01 00:00',
            'metadata'   => ['metadata'],
            'sequence'   => 3,
            'stream'     => $stream,
        ]);

        $this->assertEquals(Event\Id::fromString($uuid), $event->id());
        $this->assertEquals(new Event\Payload($payload), $event->payload());
        $this->assertEquals(new \DateTimeImmutable('2000-01-01 00:00'), $event->emittedAt());
        $this->assertEquals(new Metadata(['metadata']), $event->metadata());
        $this->assertEquals(new Metadata(['metadata']), $event->metadata());
        $this->assertSame(3, $event->sequence());
        $this->assertSame($stream, $event->stream());
    }
}
