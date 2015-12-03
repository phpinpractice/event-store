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
        if ($uuid instanceof Uuid) {
            $uuid = $uuid->toString();
        }
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
     * Compares this class to another identifier of the same class.
     *
     * @param static $identifier
     *
     * @return bool
     */
    public function equals($identifier)
    {
        return $identifier instanceof static && $this->uuid === (string)$identifier;
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
