<?php

namespace PhpInPractice\EventStore\Aggregate;

trait AggregateRootIsEventSourced
{
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

    public static function reconstitute(array $events)
    {
        $article = new static();
        foreach ($events as $event) {
            $article->apply($event);
        }

        return $article;
    }

    public function extractUncommittedEvents()
    {
        $uncommittedEvents = $this->events;
        $this->events = [];
        return $uncommittedEvents;
    }

    private function apply($event)
    {
        $eventName = substr(get_class($event), strrpos(get_class($event), '\\')+1);
        $method = 'when' . $eventName;
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
