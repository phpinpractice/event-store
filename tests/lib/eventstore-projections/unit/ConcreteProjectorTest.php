<?php

namespace PhpInPractice\EventStore\Projections;

use PhpInPractice\EventSourcing\EventStore;
use Mockery as m;
use PhpInPractice\EventStore\Stream;
use Rhumsaa\Uuid\Uuid;

/**
 * @coversDefaultClass PhpInPractice\EventStore\Projections\ConcreteProjector
 * @covers ::<private>
 */
class ConcreteProjectorTest extends \PHPUnit_Framework_TestCase
{
    /** @var EventStore|m\MockInterface */
    private $eventStore;

    /** @var Projector */
    private $projector;

    public function setUp()
    {
        $this->eventStore = m::mock(EventStore::class);
        $this->projector = new ConcreteProjector($this->eventStore);
    }

    /**
     * @test
     * @covers ::uses
     * @covers ::on
     * @covers ::project
     */
    public function it_should_project_data_from_stream()
    {
        $streamId  = Uuid::uuid4();
        $eventCode = 'eventCode';

        $event = $this->givenAnEventWithCode($eventCode, $streamId);
        $this->whenEventstoreReturnsEventFromStream([$event]);

        $this->projector->uses([$streamId]);
        $this->projector->on($eventCode, function($data) {
            $data[] = 'test';

            return $data;
        });
        $projection = $this->projector->project();

        $this->assertSame(['test'], $projection);
    }

    /**
     * @test
     * @covers ::uses
     * @covers ::on
     * @covers ::project
     */
    public function it_can_create_a_snapshot_from_projected_data()
    {
        $streamId  = Uuid::uuid4();
        $eventCode = 'eventCode';

        $event = $this->givenAnEventWithCode($eventCode, $streamId);
        $this->whenEventstoreReturnsEventFromStream([$event]);

        $this->projector->uses([$streamId]);
        $this->projector->on($eventCode, function($data) {
            $data[] = 'test';

            return $data;
        });
        $this->projector->project();

        $snapshot = $this->projector->createSnapshot();
        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertSame(['test'], $snapshot->data());
        $this->assertSame(1, $snapshot->index());
        $this->assertSame($this->projector->signature(), $snapshot->projectorSignature());
    }

    /**
     * @test
     * @covers ::uses
     * @covers ::on
     * @covers ::project
     */
    public function it_should_project_new_events_onto_a_snapshot()
    {
        $streamId  = Uuid::uuid4();
        $eventCode = 'eventCode';

        $event1 = $this->givenAnEventWithCode($eventCode, $streamId);
        $event2 = $this->givenAnEventWithCode($eventCode, $streamId);
        $this->whenEventstoreReturnsEventFromStream([$event1, $event2]);

        $this->projector->uses([$streamId]);
        $this->projector->on($eventCode, function($data) {
            $data[] = 'test2';

            return $data;
        });

        $snapshot = Snapshot::createFromArray([
            'projectorSignature' => $this->projector->signature(),
            'index' => 1,
            'data' => ['test1']
        ]);
        $projection = $this->projector->project($snapshot);

        $this->assertSame(['test1', 'test2'], $projection);
    }

    /**
     * @test
     * @covers ::uses
     * @covers ::on
     * @covers ::project
     */
    public function it_should_project_new_events_onto_an_empty_snapshot()
    {
        $streamId  = Uuid::uuid4();
        $eventCode = 'eventCode';

        $event1 = $this->givenAnEventWithCode($eventCode, $streamId);
        $event2 = $this->givenAnEventWithCode($eventCode, $streamId);
        $this->whenEventstoreReturnsEventFromStream([$event1, $event2]);

        $this->projector->uses([$streamId]);
        $this->projector->on($eventCode, function($data) {
            $data[] = 'test2';

            return $data;
        });

        $snapshot = Snapshot::createFromArray([
            'projectorSignature' => $this->projector->signature(),
            'index' => 0,
            'data' => []
        ]);
        $projection = $this->projector->project($snapshot);

        $this->assertSame(['test2', 'test2'], $projection);
    }

    /**
     * @param $eventCode
     * @param $streamId
     *
     * @return static
     */
    private function givenAnEventWithCode($eventCode, $streamId)
    {
        $event = Stream\Event::fromArray([
            'id'         => Uuid::uuid4(),
            'payload'    => [],
            'emitted_at' => '2000-01-01 00:00:00',
            'metadata'   => ['type' => $eventCode],
            'sequence'   => 1,
            'stream'     => new Stream(Stream\Id::fromString($streamId))
        ]);

        return $event;
    }

    /**
     * @param $event
     */
    private function whenEventstoreReturnsEventFromStream(array $events)
    {
        $this->eventStore
            ->shouldReceive('fetchEvents')
            ->once()
            ->with(m::type(Stream::class))->andReturn($events)
        ;
    }
}
