EventStore Aggregate
====================

The event store should not be in the way of the Write model entities.

## Initializing a Write Repository

```
$storage    = new InMemory();
$eventStore = new EventStore($storage);
$emitter    = new Emitter();

$repository = WriteRepository::create($eventStore, $emitter);
```

## Storing an Aggregate

```
// ... initialized write repository

$article = new Article('title', 'body');
$repository->persist($article);
```

## Loading an Aggregate

```
// ... initialized write repository

$repository->load(Article::class, $articleId);
```
