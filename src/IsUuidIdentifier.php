<?php

namespace PhpInPractice\EventStore;

use Assert\Assertion;
use Rhumsaa\Uuid\Uuid;

/**
 * A generic trait used with the event store to keep track of a UUID and to generate a new one conveniently.
 */
trait IsUuidIdentifier
{
    private $uuid;

    private function __construct($uuid)
    {
        Assertion::uuid($uuid);

        $this->uuid = (string)$uuid;
    }

    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    public static function fromString($uuid)
    {
        return new static($uuid);
    }

    public function __toString()
    {
        return $this->uuid;
    }
}
