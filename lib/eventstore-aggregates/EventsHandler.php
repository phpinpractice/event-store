<?php

namespace PhpInPractice\EventStore\Aggregate;

interface EventsHandler
{
    public function extractUncommittedEvents($aggregate);
    public function reconstitute($aggregateRootClassName, array $events);
}
