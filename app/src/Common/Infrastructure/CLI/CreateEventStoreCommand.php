<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\CLI;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the event store schema.
 */
class CreateEventStoreCommand extends Command
{
    private Connection $connection;

    private DBALEventStore $eventStore;

    public function __construct(Connection $connection, DBALEventStore $eventStore)
    {
        $this->connection = $connection;
        $this->eventStore = $eventStore;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('broadway:event-store:create')
            ->setDescription('Creates the event store schema')
            ->setHelp(
                <<<'EOT'
    The <info>%command.name%</info> command creates the schema in the default
    connections database:

    <info>php app/console %command.name%</info>
    EOT
            );
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($table = $this->eventStore->configureSchema($schemaManager->createSchema())) {
            $schemaManager->createTable($table);
            $output->writeln('<info>Created Broadway event store schema</info>');
        } else {
            $output->writeln('<info>Broadway event store schema already exists</info>');
        }

        return 0;
    }
}
