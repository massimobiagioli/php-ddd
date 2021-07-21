<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\CLI;

use App\MarsRover\Infrastructure\ReadModel\DBALRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the read model table.
 */
class CreateReadModelCommand extends Command
{
    private DBALRepository $repository;

    private Connection $connection;

    public function __construct(Connection $connection, DBALRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('broadway:read-model:create')
            ->setDescription('Creates the read model table');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($table = $this->repository->configureSchema($schemaManager->createSchema())) {
            $schemaManager->createTable($table);
            $output->writeln('<info>Created Broadway read model schema</info>');
        } else {
            $output->writeln('<info>Broadway read model schema already exists</info>');
        }

        return 0;
    }
}
