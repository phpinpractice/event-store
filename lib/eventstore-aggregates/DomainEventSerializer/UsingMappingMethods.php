<?php

namespace PhpInPractice\EventStore\Aggregate\DomainEventSerializer;

use PhpInPractice\EventStore\Aggregate\DomainEventSerializer as Base;

class UsingMappingMethods implements Base
{
    public function fromArray($eventClass, array $data)
    {
        return $eventClass::fromArray($data);
    }

    public function toArray($event)
    {
        return $event->toArray();
    }
}
