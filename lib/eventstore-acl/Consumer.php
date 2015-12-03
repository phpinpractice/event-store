<?php

namespace PhpInPractice\EventStore\Acl;

use PhpInPractice\EventStore\Aggregate\AggregateRootIsEventSourced;

final class Consumer
{
    use AggregateRootIsEventSourced;

    private $id;
    private $token;

    /** @var Privileges[] */
    private $privileges = [];

    private function __construct()
    {
    }

    public function id()
    {
        return $this->id;
    }

    public function token()
    {
        return $this->token;
    }

    public function privileges()
    {
        return $this->privileges;
    }

    public static function register()
    {
        $consumer = new static();
        $consumer->when(new ConsumerWasRegistered(Consumer\Id::generate()));

        return $consumer;
    }

    public function grant(Privilege $privilege)
    {
        $this->when(new PrivilegeWasGranted($this->id, $this->privileges()->streamId(), $privilege));
    }

    private function whenConsumerWasRegistered(ConsumerWasRegistered $event)
    {
        $this->id = $event->id();
    }

    private function whenPrivilegeWasGranted(PrivilegeWasGranted $event)
    {
        if (! array_key_exists($event->streamId(), $this->privileges)) {
            $this->privileges[(string)$event->streamId()] = new Privileges($event->streamId());
        }

        $this->privileges[(string)$event->streamId()]->add($event->privilege());
    }
}
