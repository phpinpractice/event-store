<?php

namespace PhpInPractice\EventStore\Aggregate;

use Assert\Assertion;
use PhpInPractice\EventStore\EventStorage;
use PhpInPractice\EventStore\Metadata;
use PhpInPractice\EventStore\Stream;

class WriteRepository
{
    /** @var EventStorage */
    private $eventStore;

    /** @var string|AggregateRootIsEventSourced */
    private $aggregateRootClassName;

    /** @var IdentifierExtractor */
    private $identifierExtractor;

    /** @var DomainEventSerializer */
    private $domainEventSerializer;

    /** @var EventsHandler */
    private $eventsHandler;

    /** @var Emitter */
    private $emitter;

    /**
     * Initializes this repository with the event store, which type of aggregate to support and
     * a series of strategies on how to handle the aggregate and events.
     *
     * @param EventStorage          $eventStore
     * @param string                $aggregateRootClassName The name of an existing class of the Aggregate that is
     *                                                      handled by this repository.
     * @param Emitter               $emitter
     * @param IdentifierExtractor   $identifierExtractor    Strategy that is capable of extracting the id from the
     *                                                      aggregate.
     * @param DomainEventSerializer $domainEventSerializer  Generic serializer that converts payloads, represented by
     *                                                      an array, to an Event and back.
     * @param EventsHandler         $eventsHandler          Strategy that determines how to reconstitute an aggregate
     *                                                      and how to extract any uncommitted events from the
     *                                                      aggregate.
     */
    public function __construct(
        EventStorage $eventStore,
        $aggregateRootClassName,
        Emitter $emitter = null,
        IdentifierExtractor $identifierExtractor,
        DomainEventSerializer $domainEventSerializer,
        EventsHandler $eventsHandler
    ) {
        Assertion::classExists($aggregateRootClassName);

        $this->eventStore             = $eventStore;
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->emitter                = $emitter;
        $this->identifierExtractor    = $identifierExtractor;
        $this->domainEventSerializer  = $domainEventSerializer;
        $this->eventsHandler          = $eventsHandler;
    }

    /**
     * Creates a new Write Repository using the recommended strategies.
     *
     * @param EventStorage $eventStore
     * @param string       $aggregateRootClassName
     * @param Emitter      $emitter
     *
     * @return static
     */
    public static function create(EventStorage $eventStore, $aggregateRootClassName, Emitter $emitter = null)
    {
        return new static(
            $eventStore,
            $aggregateRootClassName,
            $emitter,
            new IdentifierExtractor\UsingIdMethod(),
            new DomainEventSerializer\UsingMappingMethods(),
            new EventsHandler\PublicMethods()
        );
    }

    /**
     * Retrieves an aggregate root object for the given identifier or null if none was found.
     *
     * @param string $id
     *
     * @return object|null
     */
    public function load($id)
    {
        $stream        = new Stream(Stream\Id::fromString((string)$id));
        $articleEvents = $this->eventStore->fetchEvents($stream);

        if (empty($articleEvents)) {
            return null;
        }

        $domainEvents = [];
        foreach ($articleEvents as $storeEvent) {
            $domainEvents[] = $this->domainEventSerializer->fromArray(
                $storeEvent->metadata()['type'],
                $storeEvent->payload()->getArrayCopy()
            );
        }

        return $this->eventsHandler->reconstitute($this->aggregateRootClassName, $domainEvents);
    }

    public function persist($aggregateRoot)
    {
        $aggregateRootId = $this->identifierExtractor->extract($aggregateRoot);
        $stream          = new Stream(Stream\Id::fromString($aggregateRootId));

        $events = $this->eventsHandler->extractUncommittedEvents($aggregateRoot);
        foreach ($events as $domainEvent) {
            $event = new Stream\Event(
                Stream\Event\Id::generate(),
                new Stream\Event\Payload($this->domainEventSerializer->toArray($domainEvent)),
                new \DateTimeImmutable(),
                new Metadata(['type' => get_class($domainEvent)])
            );

            $this->eventStore->persist($stream, [$event]);
            if ($this->emitter) {
                $this->emitter->emit($domainEvent);
            }
        }
    }
}
