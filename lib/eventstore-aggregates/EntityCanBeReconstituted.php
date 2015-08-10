<?php

namespace PhpInPractice\EventStore\Aggregate;

trait EntityCanBeReconstituted
{
    public static function reconstitute(array $events)
    {
        $entity = new static();
        foreach ($events as $event) {
            $entity->apply($event);
        }

        return $entity;
    }

    public function apply($event)
    {
        $eventName = substr(get_class($event), strrpos(get_class($event), '\\')+1);
        $method = 'when' . $eventName;
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
