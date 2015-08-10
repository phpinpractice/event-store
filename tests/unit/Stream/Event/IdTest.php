<?php

namespace PhpInPractice\EventStore\Stream\Event;

use Assert\Assertion;
use Rhumsaa\Uuid\Uuid;

/**
 * @coversDefaultClass PhpInPractice\EventStore\Stream\Event\Id
 */
class IdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::generate
     * @covers ::__toString
     * @test
     */
    public function a_new_id_can_be_generate()
    {
        $id = Id::generate();

        $this->assertInstanceOf(Id::class, $id);
        $this->assertNotEmpty((string)$id);
        Assertion::uuid((string)$id);
    }

    /**
     * @covers ::__construct
     * @covers ::fromString
     * @covers ::__toString
     * @test
     */
    public function it_can_be_reconstituted_from_string()
    {
        $uuid = Uuid::uuid4();
        $id = Id::fromString($uuid);

        $this->assertInstanceOf(Id::class, $id);
        $this->assertNotEmpty((string)$id);
        $this->assertSame((string)$uuid, (string)$id);
    }
}
