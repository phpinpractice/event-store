<?php

namespace PhpInPractice\EventStore\Projections;

use PhpInPractice\EventStore\EventStore;

class Builder
{
    /** @var EventStore */
    private $eventStore;

    /** @var ConcreteProjector */
    private $projector;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function create(array $fromStreams, $initialData = [])
    {
        $this->projector = new ConcreteProjector($this->eventStore, $initialData);
        $this->projector->uses($fromStreams);

        return $this;
    }

    public function once(Specification $specification)
    {
        $this->projector->filterUsing($specification);

        return $this;
    }

    public function when($eventCode, callable $projectorAction)
    {
        $this->projector->on($eventCode, $projectorAction);

        return $this;
    }

    public function getProjector()
    {
        return $this->projector;
    }
}
