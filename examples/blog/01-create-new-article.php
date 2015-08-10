<?php
use PhpInPractice\EventStore\Aggregate\WriteRepository;
use PhpInPractice\EventStore\EventStore;
use PhpInPractice\EventStore\Storage\InMemory;
use PhpInPractice\EventStore\Example\Blog\Article;
use PhpInPractice\EventStore\Example\Blog\ArticleRepository;

include __DIR__ . '/../../vendor/autoload.php';

$eventStore = new EventStore(new InMemory());

$article = Article::post('An amazing introduction into Event Sourcing', 'Just look at all these examples!');

$repository = new ArticleRepository(WriteRepository::create($eventStore, Article::class));
$repository->persist($article);

var_dump($repository->find($article->id()));
