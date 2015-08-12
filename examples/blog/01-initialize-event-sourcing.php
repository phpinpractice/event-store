<?php
/**
 * Example on how to initialize event sourcing with this event store.
 */
use PhpInPractice\EventStore\Aggregate\Emitter;
use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Storage\InMemory;

include __DIR__ . '/../../vendor/autoload.php';

// Initialize the storage for the event store; this dictates where events are written to.
// The InMemory adapter is only usuable for testing purposes since it doesn't persist events,
// for production environments you can use, for example, the Doctrine adapter.
$storage    = new InMemory();

// Initialize the event store with the storage where events should be persisted to.
$eventStore = new EventStore($storage);

// The Emitter is capable of emitting your Domain Events to projectors and other objects that
// should be able to read the event stream.
$emitter = new Emitter();
