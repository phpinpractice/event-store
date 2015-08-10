<?php

namespace PhpInPractice\EventStore;

use Mockery as m;

/**
 * @coversDefaultClass PhpInPractice\EventStore\EventStore
 */
class EventStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::fetchEvents
     * @uses PhpInPractice\EventStore\Stream
     * @uses PhpInPractice\EventStore\Stream\Id
     * @test
     */
    public function it_fetches_events_for_a_specific_stream()
    {
        $stream     = $this->givenAStream();
        $expected   = ['abc'];
        $storage    = m::mock(StorageAdapter::class);
        $storage->shouldReceive('fetchEventsForStream')->with($stream)->andReturn($expected);
        $eventStore = new EventStore($storage);

        $events = $eventStore->fetchEvents($stream);

        $this->assertSame($expected, $events);
    }

    /**
     * @covers ::__construct
     * @covers ::persist
     * @uses PhpInPractice\EventStore\Stream
     * @uses PhpInPractice\EventStore\Stream\Id
     * @test
     */
    public function it_persists_the_events_to_storage()
    {
        $stream     = $this->givenAStream();
        $event      = new Stream\Event(Stream\Event\Id::generate(), new Stream\Event\Payload());
        $storage    = m::mock(StorageAdapter::class);
        $storage->shouldReceive('persist')->with(m::on(function($events) use ($stream) {
            $this->assertCount(1, $events);
            $this->assertAttributeSame(1, 'sequence', $events[0]);
            $this->assertAttributeSame($stream, 'stream', $events[0]);

            return true;
        }));
        $eventStore = new EventStore($storage);

        $this->assertAttributeSame(null, 'sequence', $stream);
        $eventStore->persist($stream, [$event]);
        $this->assertAttributeSame(1, 'sequence', $stream);
    }

    /**
     * Returns a new instance of a stream;
     *
     * @return Stream
     */
    private function givenAStream()
    {
        return new Stream(Stream\Id::generate());
    }
}
