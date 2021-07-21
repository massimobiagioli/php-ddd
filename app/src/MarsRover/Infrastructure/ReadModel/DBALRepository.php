<?php

declare(strict_types=1);

namespace App\MarsRover\Infrastructure\ReadModel;

use Assert\Assertion;
use Broadway\ReadModel\Identifiable;
use Broadway\ReadModel\Repository;
use Broadway\Serializer\Serializer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

class DBALRepository implements Repository
{
    private Connection $connection;

    private Serializer $serializer;

    private string $tableName;

    private string $class;

    public function __construct(
        Connection $connection,
        Serializer $serializer,
        string $tableName,
        string $class
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->tableName = $tableName;
        $this->class = $class;
    }

    public function save(Identifiable $readModel): void
    {
        Assertion::isInstanceOf($readModel, $this->class);

        if ($this->find($readModel->getId())) {
            $this->connection->update(
                $this->tableName,
                ['data' => json_encode($this->serializer->serialize($readModel))],
                ['uuid' => $readModel->getId()]
            );
        } else {
            $this->connection->insert($this->tableName, [
                'uuid' => $readModel->getId(),
                'data' => json_encode($this->serializer->serialize($readModel)),
            ]);
        }
    }

    public function find($id): ?Identifiable
    {
        $row = $this->connection->fetchAssociative(sprintf('SELECT * FROM %s WHERE uuid = ?', $this->tableName), [$id]);
        if (false === $row) {
            return null;
        }

        return $this->serializer->deserialize(json_decode($row['data'], true));
    }

    public function findBy(array $fields): array
    {
        if (empty($fields)) {
            return [];
        }

        return array_values(array_filter($this->findAll(), function (Identifiable $readModel) use ($fields) {
            return $fields === array_intersect_assoc($this->serializer->serialize($readModel)['payload'], $fields);
        }));
    }

    public function findAll(): array
    {
        $rows = $this->connection->fetchAllAssociative(sprintf('SELECT * FROM %s', $this->tableName));

        return array_map(function (array $row) {
            return $this->serializer->deserialize(json_decode($row['data'], true));
        }, $rows);
    }

    public function remove($id): void
    {
        $this->connection->executeUpdate(sprintf('DELETE FROM %s WHERE uuid = ?', $this->tableName), [$id]);
    }

    public function configureSchema(Schema $schema): ?Table
    {
        if ($schema->hasTable($this->tableName)) {
            return null;
        }

        return $this->configureTable($schema);
    }

    public function configureTable(Schema $schema): Table
    {
        $table = $schema->createTable($this->tableName);
        $table->addColumn('uuid', 'guid', ['length' => 36]);
        $table->addColumn('data', 'text');
        $table->setPrimaryKey(['uuid']);

        return $table;
    }
}
