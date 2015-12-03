<?php

namespace PhpInPractice\EventStore\Acl;

use PhpInPractice\EventStore\Stream;

class PrivilegeWasGranted
{
    /** @var Consumer\Id */
    private $consumerId;

    /** @var Stream\Id */
    private $streamId;

    /** @var Privilege */
    private $privilege;

    public function __construct(Consumer\Id $consumerId, Stream\Id $streamId, Privilege $privilege)
    {
        $this->consumerId = $consumerId;
        $this->streamId   = $streamId;
        $this->privilege  = $privilege;
    }

    public function id()
    {
        return $this->consumerId;
    }

    public function streamId()
    {
        return $this->streamId;
    }

    public function privilege()
    {
        return $this->privilege;
    }

    public function toArray()
    {
        return [
            'id'        => (string)$this->consumerId,
            'streamId'  => (string)$this->streamId,
            'privilege' => (string)$this->privilege
        ];
    }

    public static function fromArray(array $data)
    {
        return new static(
            Consumer\Id::fromString($data['id']),
            Stream\Id::fromString($data['streamId']),
            Privilege::fromString($data['privilege'])
        );
    }
}
