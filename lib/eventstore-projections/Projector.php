<?php
namespace PhpInPractice\EventStore\Projections;

use Rb\Specification\SpecificationInterface as Specification;

interface Projector
{
    public function uses(array $streams);

    public function filterUsing(Specification $specification);

    public function on($eventCode, callable $projectorAction);

    public function project(Snapshot $fromSnapshot = null);

    /**
     * Calculate a signature with which to determine if a snapshot can be loaded on this projector.
     *
     * Whenever a projector's projection parameters change (such as which streams to read from, what events
     * to filter and which events to project) is an earlier created snapshot no longer valid. This means that
     * when this occurs that any given snapshot is to be disregarded and a complete re-projection needs to be done.
     *
     * @return string
     */
    public function signature();

    public function lastIndex();

    public function projection();

    /**
     * @return Snapshot
     */
    public function createSnapshot();
}
