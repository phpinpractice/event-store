---
category: Event Store
---

# Getting Started

## Setting up your event store

### Select your data storage method

```
$doctrineConnection = <..Create Doctrine Connection To Your Favourite Database..>;
$adapter = new \PhpInPractice\EventStore\Storage\Doctrine($doctrineConnection); 
```

> For testing purposes or storing events for the duration of a session you can also 
> use the InMemory storage adapter. This adapter will store all events in an array 
> in memory for the duration of your request. This means that after the request the 
> events may be lost.
>
> ```
> $adapter = new \PhpInPractice\EventStore\Storage\InMemory();
> ```

### Create the Event Store

```
$eventStore = new \PhpInPractice\EventStore\EventStore($adapter);
```

### Your first stream

