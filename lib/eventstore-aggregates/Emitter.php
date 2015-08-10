<?php

namespace PhpInPractice\EventStore\Aggregate;

class Emitter
{
    /** @var object[] */
    private $projectors;

    public function subscribe($projector)
    {
        $this->projectors[] = $projector;
    }

    public function emit($domainEvent)
    {
        foreach ($this->projectors as $projector)
        {
            // TODO: a strategy should determine how to push an event to a projector; or an adapter should do this?
            $projector->apply($domainEvent);
        }
    }
}
