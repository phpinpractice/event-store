<?php

namespace PhpInPractice\EventStore\Aggregate;

trait AggregateRootIsEventSourced
{
    use EntityCanBeReconstituted;

    private $events = [];

    /**
     * @param $event
     * @param $article
     */
    private function when($event)
    {
        $this->events[] = $event;
        $this->apply($event);
    }

    public function extractUncommittedEvents()
    {
        $uncommittedEvents = $this->events;
        $this->events = [];
        return $uncommittedEvents;
    }
}
