<?php

namespace PhpInPractice\EventStore\Projections;

use Mockery as m;

/**
 * @coversDefaultClass PhpInPractice\EventStore\Projections\Snapshot
 * @covers ::<private>
 */
class SnapshotTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::createFromProjector
     * @covers ::index
     * @covers ::data
     * @covers ::projectorSignature
     */
    public function it_should_be_created_from_projector()
    {
        $index = 1;
        $data = ['myData'];
        $signature = '123';

        $projector = m::mock(Projector::class);
        $projector->shouldReceive('lastIndex')->andReturn($index);
        $projector->shouldReceive('projection')->andReturn($data);
        $projector->shouldReceive('signature')->andReturn($signature);

        $snapshot = Snapshot::createFromProjector($projector);
        $this->assertSame($index, $snapshot->index());
        $this->assertSame($data, $snapshot->data());
        $this->assertSame($signature, $snapshot->projectorSignature());
    }
}
