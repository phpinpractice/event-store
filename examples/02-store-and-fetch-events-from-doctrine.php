<?php
use Doctrine\DBAL\DriverManager;
use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Storage\Doctrine;
use PhpInPractice\EventStore\Stream;
use PhpInPractice\EventStore\Stream\Event;

require_once(__DIR__ . '/../vendor/autoload.php');

$connection = DriverManager::getConnection([ 'url' => 'mysql://root:secret@192.168.59.103/eventstore' ]);
$storage    = new Doctrine($connection);
$storage->initialize();

$eventStore = new EventStore($storage);
$stream     = new Stream(Stream\Id::generate());

$eventStore->persist(
    $stream,
    [
        new Event(Event\Id::generate(), new Event\Payload()),
        new Event(Event\Id::generate(), new Event\Payload())
    ]
);
$eventStore->persist($stream, [ new Event(Event\Id::generate(), new Event\Payload()) ]);

$events = $eventStore->fetchEvents($stream);

var_export($events);
