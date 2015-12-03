<?php

namespace PhpInPractice\EventStore\Acl;

class ConsumerWasRegistered
{
    /** @var Consumer\Id */
    private $id;

    public function __construct(Consumer\Id $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id();
    }

    public function toArray()
    {
        return [ 'id'=> (string)$this->id ];
    }

    public static function fromArray(array $data)
    {
        return new static(Consumer\Id::fromString($data['id']));
    }
}
