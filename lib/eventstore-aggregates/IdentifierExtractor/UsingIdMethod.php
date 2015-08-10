<?php

namespace PhpInPractice\EventStore\Aggregate\IdentifierExtractor;

use PhpInPractice\EventStore\Aggregate\IdentifierExtractor as Base;

class UsingIdMethod implements Base
{
    public function extract($aggregate)
    {
        return (string)$aggregate->id();
    }
}
