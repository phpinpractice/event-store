<?php

namespace PhpInPractice\EventStore\Aggregate;

interface DomainEventSerializer
{
    public function fromArray($eventClass, array $data);

    public function toArray($event);
}
