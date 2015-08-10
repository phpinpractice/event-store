<?php

namespace PhpInPractice\EventStore\Aggregate;

interface IdentifierExtractor
{
    public function extract($aggregate);
}
