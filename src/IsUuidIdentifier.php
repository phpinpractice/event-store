<?php

namespace PhpInPractice\EventStore;

use Assert\Assertion;
use Rhumsaa\Uuid\Uuid;

/**
 * A generic trait used with the event store to keep track of a UUID and to generate a new one conveniently.
 */
trait IsUuidIdentifier
{
    /** @var string A UUID v4 identifier */
    private $uuid;

    /**
     * Creates a new identifier with the given UUID.
     *
     * @param Uuid|string $uuid
     */
    private function __construct($uuid)
    {
        Assertion::uuid($uuid);

        $this->uuid = (string)$uuid;
    }

    /**
     * Generates a new identifier.
     *
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * Reconstitutes an identifier object with the given UUID.
     *
     * @param string|Uuid $uuid
     *
     * @return static
     */
    public static function fromString($uuid)
    {
        return new static($uuid);
    }

    /**
     * Returns the underlying UUID for serialisation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uuid;
    }
}
