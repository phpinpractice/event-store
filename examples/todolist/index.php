<?php

use Doctrine\DBAL\DriverManager;
use PhpInPractice\EventStore\Storage\Doctrine;
use PhpInPractice\EventStore\Stream;

require_once('../../vendor/autoload.php');

$connection = DriverManager::getConnection(['url' => 'sqlite:////tmp/es-todo.sqlite']);
$storage = new Doctrine($connection);
$eventStore = new \PhpInPractice\EventStore\EventStore($storage);

$stream = new Stream(Stream\Id::generate());
