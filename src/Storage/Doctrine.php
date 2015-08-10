<?php

namespace PhpInPractice\EventStore\Storage;

use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PhpInPractice\EventStore\StorageAdapter;
use PhpInPractice\EventStore\Stream;

final class Doctrine implements StorageAdapter
{
    const DEFAULT_TABLENAME = 'events';
    const OPTION_TABLENAME  = 'table_name';

    /** @var DriverConnection */
    private $connection;

    /** @var string[] */
    private $options;

    public function __construct(DriverConnection $connection, array $options = [])
    {
        if (! isset($options[self::OPTION_TABLENAME])) {
            $options[self::OPTION_TABLENAME] = self::DEFAULT_TABLENAME;
        }

        $this->connection = $connection;
        $this->options    = $options;
    }

    public function initialize(array $options = [])
    {
        $tableName = $this->options[self::OPTION_TABLENAME];
        if (! method_exists($this->connection, 'getSchemaManager')) {
            throw new \RuntimeException(
                'The provided connection does not support query building, please choose a different connection type '
                . 'that does'
            );
        }

        if ($this->connection->getSchemaManager()->tablesExist([$tableName])) {
            return;
        }

        $table = new Table($tableName);
        $table->addColumn('id', Type::STRING, [ 'length' => '36' ]);
        $table->addColumn('stream_id', Type::STRING, [ 'length' => '36' ]);
        $table->addColumn('sequence', Type::BIGINT);
        $table->addColumn('payload', Type::TEXT);
        $table->addColumn('emitted_at', Type::DATETIME);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['stream_id']);
        $table->addUniqueIndex(['stream_id', 'sequence']);

        $this->connection->getSchemaManager()->createTable($table);
    }

    /**
     * @inheritDoc
     */
    public function fetchEventsForStream(Stream $stream)
    {
        if (! method_exists($this->connection, 'createQueryBuilder')) {
            throw new \RuntimeException(
                'The provided connection does not support query building, please choose a different connection type '
                . 'that does'
            );
        }

        /** @var QueryBuilder $query */
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from($this->options[self::OPTION_TABLENAME])
            ->where('stream_id = :streamId')
            ->orderBy('sequence', 'DESC');
        $query->setParameter('streamId', (string)$stream->id());
        $events = $query->execute();

        $result = [];
        while($event = $events->fetch()) {
            $result[] = Stream\Event::fromArray([
                'id'         => (string)$event['id'],
                'stream'     => $stream,
                'emitted_at' => $event['emitted_at'],
                'payload'    => json_decode($event['payload']),
                'sequence'   => $event['sequence'],
                'metadata'   => [],
            ]);
            $stream->moveHeadTo($event['sequence']);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function persist(array $uncommittedEvents)
    {
        if (! method_exists($this->connection, 'insert')) {
            throw new \RuntimeException(
                'The provided connection does not support inserting records using the "insert" method, please '
                . 'choose a different connection type that does'
            );
        }

        /** @var Stream\Event $event */
        foreach ($uncommittedEvents as $event) {
            $this->connection->insert(
                $this->options[self::OPTION_TABLENAME],
                [
                    'id'         => (string)$event->id(),
                    'stream_id'  => (string)$event->stream()->id(),
                    'sequence'   => $event->sequence(),
                    'payload'    => json_encode($event->payload()),
                    'emitted_at' => $event->emittedAt()->format('Y-m-d H:i:s')
                ]
            );
        }
    }
}
