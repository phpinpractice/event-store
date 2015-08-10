<?php
use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Storage\InMemory;
use PhpInPractice\EventStore\Stream;
use PhpInPractice\EventStore\Stream\Event;

require_once(__DIR__ . '/../vendor/autoload.php');

$eventStore = new EventStore(new InMemory());
$stream     = new Stream(Stream\Id::generate());

$eventStore->persist($stream, [ new Event(Event\Id::generate(), new Event\Payload()) ]);
$eventStore->persist($stream, [ new Event(Event\Id::generate(), new Event\Payload()) ]);

$events = $eventStore->fetchEvents($stream);

var_export($events);
