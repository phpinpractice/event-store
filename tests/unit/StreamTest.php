<?php

namespace PhpInPractice\EventStore;

/**
 * @coversDefaultClass PhpInPractice\EventStore\Stream
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::id
     * @uses PhpInPractice\EventStore\Stream\Id
     * @test
     */
    public function it_has_an_identifier()
    {
        $id     = Stream\Id::generate();
        $stream = new Stream($id);

        $this->assertSame($id, $stream->id());
    }

    /**
     * @covers ::incrementHead
     * @uses PhpInPractice\EventStore\Stream::__construct
     * @uses PhpInPractice\EventStore\Stream\Id
     * @test
     */
    public function it_increments_and_returns_the_sequence_number_for_the_head_of_the_stream()
    {
        $stream = new Stream(Stream\Id::generate());
        $this->assertSame(1, $stream->incrementHead());
        $this->assertSame(2, $stream->incrementHead());
    }

    /**
     * @covers ::moveHeadTo
     * @uses PhpInPractice\EventStore\Stream::__construct
     * @uses PhpInPractice\EventStore\Stream::incrementHead
     * @uses PhpInPractice\EventStore\Stream\Id
     * @test
     */
    public function it_moves_the_sequence_number_to_a_new_head()
    {
        $stream = new Stream(Stream\Id::generate());
        $stream->moveHeadTo(4);
        $this->assertSame(5, $stream->incrementHead());
    }
}
