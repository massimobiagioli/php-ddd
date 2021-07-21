<?php

declare(strict_types=1);

namespace App\MarsRover\Infrastructure\ReadModel;

use Broadway\ReadModel\Repository;
use Broadway\ReadModel\RepositoryFactory;
use Broadway\Serializer\Serializer;
use Doctrine\DBAL\Connection;

class DBALRepositoryFactory implements RepositoryFactory
{
    private Connection $connection;

    private Serializer $serializer;

    private string $tableName;

    public function __construct(
        Connection $connection,
        Serializer $serializer,
        $tableName
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->tableName = $tableName;
    }

    public function create(string $name, string $class): Repository
    {
        return new DBALRepository($this->connection, $this->serializer, $this->tableName, $class);
    }
}
