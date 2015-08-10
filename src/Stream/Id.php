<?php

namespace PhpInPractice\EventStore\Stream;

use PhpInPractice\EventStore\IsUuidIdentifier;

/**
 * Class representing an Identifier for a Stream.
 */
final class Id
{
    use IsUuidIdentifier;
}
