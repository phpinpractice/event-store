<?php

namespace PhpInPractice\EventStore\Projections;

final class Snapshot
{
    private $index;
    private $data;
    private $projectorSignature;

    public static function createFromProjector(Projector $projector)
    {
        return self::createFromArray([
            'projectorSignature' => $projector->signature(),
            'index' => $projector->lastIndex(),
            'data' => $projector->projection()
        ]);
    }

    public static function createFromArray(array $snapshotData)
    {
        $snapshot = new static();
        $snapshot->projectorSignature = $snapshotData['projectorSignature'];
        $snapshot->index = $snapshotData['index'];
        $snapshot->data = $snapshotData['data'];

        return $snapshot;
    }

    public function index()
    {
        return $this->index;
    }

    public function data()
    {
        return $this->data;
    }

    public function projectorSignature()
    {
        return $this->projectorSignature;
    }
}
