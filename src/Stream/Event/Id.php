<?php

namespace PhpInPractice\EventStore\Stream\Event;

use PhpInPractice\EventStore\IsUuidIdentifier;

/**
 * Class representing an Identifier for a single Event.
 */
final class Id
{
    use IsUuidIdentifier;
}
