<?php

namespace PhpInPractice\EventStore\Acl;

use PhpInPractice\EventStore\Stream;

final class Privileges
{
    private $streamId;
    private $privileges = [];

    public function __construct(Stream\Id $streamId)
    {
        $this->streamId = $streamId;
    }

    public function streamId()
    {
        return $this->streamId;
    }

    public function add(Privilege $privilege)
    {
        $this->privileges[] = $privilege;
    }

    public function isGranted(Privilege $privilege)
    {
        foreach ($this->privileges as $grantedPrivilege) {
            if ($privilege->equals($grantedPrivilege)) {
                return true;
            }
        }

        return false;
    }
}
