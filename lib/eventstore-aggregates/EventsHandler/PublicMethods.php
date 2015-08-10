<?php

namespace PhpInPractice\EventStore\Aggregate\EventsHandler;

use PhpInPractice\EventStore\Aggregate\EventsHandler as Base;

class PublicMethods implements Base
{
    public function extractUncommittedEvents($aggregate)
    {
        return $aggregate->extractUncommittedEvents();
    }

    public function reconstitute($aggregateRootClassName, array $events)
    {
        return $aggregateRootClassName::reconstitute($events);
    }
}
