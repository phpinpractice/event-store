<?php

namespace PhpInPractice\EventStore\Projections;

use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Stream;
use Rb\Specification\SpecificationInterface as Specification;

final class ConcreteProjector implements Projector
{
    /** @var EventStore */
    private $eventStore;

    /** @var array|object */
    private $projection;

    /** @var string[] */
    private $streams;

    /** @var Specification */
    private $specification;

    /** @var callable[] */
    private $actions;

    /** @var integer */
    private $lastIndex = 0;

    /**
     * Projector constructor.
     *
     * @param EventStore $eventStore
     * @param array      $initialData
     */
    public function __construct($eventStore, $initialData = [])
    {
        $this->eventStore = $eventStore;
        $this->projection = $initialData;
    }

    public function uses(array $streams)
    {
        $this->streams = $streams;
    }

    public function filterUsing(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function on($eventCode, callable $projectorAction)
    {
        $this->actions[$eventCode] = $projectorAction;
    }

    public function project(Snapshot $fromSnapshot = null)
    {
        if ($fromSnapshot instanceof Snapshot && $fromSnapshot->projectorSignature() === $this->signature()) {
            $this->projection = $fromSnapshot->data();
            $this->lastIndex = $fromSnapshot->index();
        }

        $eventSources = [];
        foreach ($this->streams as $streamId) {
            $stream = new Stream(Stream\Id::fromString((string)$streamId));
            $eventSources = array_merge($eventSources, $this->eventStore->fetchEvents($stream));
        }

        $events = $this->removeProjectedEvents(
            $this->sortEvents(
                $this->filterEventsBasedOnSpecification($eventSources)
            )
        );

        // Apply event on projection
        foreach ($events as $event) {
            $this->applyEventOnProjection($event);
        }

        return $this->projection();
    }

    /**
     * Calculate a signature with which to determine if a snapshot can be loaded on this projector.
     *
     * Whenever a projector's projection parameters change (such as which streams to read from, what events
     * to filter and which events to project) is an earlier created snapshot no longer valid. This means that
     * when this occurs that any given snapshot is to be disregarded and a complete re-projection needs to be done.
     *
     * @return string
     */
    public function signature()
    {
        return md5(serialize($this->streams) . serialize($this->specification) . serialize(array_keys($this->actions)));
    }

    public function lastIndex()
    {
        return $this->lastIndex;
    }

    public function projection()
    {
        return $this->projection;
    }

    public function createSnapshot()
    {
        return Snapshot::createFromProjector($this);
    }

    /**
     * @todo re-evaluate this sorting mechanism; I am not sure if it is exact.
     *
     * @param Stream\Event[] $events
     *
     * @return Stream\Event[]
     */
    private function sortEvents(array $events)
    {
        usort($events, function ($v1, $v2) {
            /**
             * @var Stream\Event $v1
             * @var Stream\Event $v2
             * @TODO: Check if microseconds are used?!
             */
            return $v1->emittedAt() < $v2->emittedAt() ? -1 : ($v1->emittedAt() > $v2->emittedAt() ? 1 : 0);
        });

        return $events;
    }

    /**
     * @param Stream\Event[] $eventSources
     *
     * @return Stream\Event[]
     */
    private function filterEventsBasedOnSpecification(array $eventSources)
    {
        if (! $this->specification) {
            return $eventSources;
        }

        $events = [];
        foreach ($eventSources as $event) {
            if (! $this->specification->isSatisfiedBy($event)) {
                continue;
            }

            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param Stream\Event[] $events
     *
     * @return array
     */
    private function removeProjectedEvents(array $events)
    {
        return array_slice($events, $this->lastIndex);
    }

    /**
     * @param $event
     */
    private function applyEventOnProjection(Stream\Event $event)
    {
        if (isset($this->actions[$event->metadata()['type']])) {
            $this->projection = $this->actions[$event->metadata()['type']]($this->projection, $event);
            $this->lastIndex++;
        }
    }
}
