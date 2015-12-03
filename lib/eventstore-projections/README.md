# Projections

## Projection Builder

```
$projection = new \stdClass();
$projection->userCount = 0;

$builder = new PhpInPractice\Eventstore\Projection\Builder($eventStore);
$projector = $builder
    ->create(['StreamA', 'StreamB'], $projection)
    ->once(Projector::metadataValueEquals('aggregate_id', '1234567890'))
    ->when(UserWasRegistered::class, function($projection, UserWasRegistered event) { $projection->userCount++; })
```

## Projecting events

```
$projection = $projector->project();
```

## Snapshotting

```
// ... Create projector using builder

$snapshotRepository = new \PhpInPractice\Eventstore\Projection\SnapshotRepository();
$projection         = $projector->project($snapshotRepository->fetchByProjector($projector));

$snapshotRepository->persist($projector->createSnapshot());
```
