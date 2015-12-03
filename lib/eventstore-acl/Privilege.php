<?php

namespace PhpInPractice\EventStore\Acl;

use Assert\Assertion;

final class Privilege
{
    private $type;

    private function __construct($type)
    {
        Assertion::choice($type, ['read', 'write']);

        $this->type = $type;
    }

    public static function allowsReading()
    {
        return new static('read');
    }

    public static function allowsWriting()
    {
        return new static('write');
    }

    public static function fromString($string)
    {
        return new static($string);
    }

    public function allowRead()
    {
        return $this->type === 'read';
    }

    public function allowWrite()
    {
        return $this->type === 'write';
    }

    public function equals($grantedPrivilege)
    {
        return $this->type === (string)$grantedPrivilege;
    }

    public function __toString()
    {
        return $this->type;
    }
}
