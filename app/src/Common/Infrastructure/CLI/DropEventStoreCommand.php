<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\CLI;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Drops the event store schema.
 */
class DropEventStoreCommand extends Command
{
    private Connection $connection;
    /**
     * @var EventStore
     */
    private $eventStore;

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
            ->setName('broadway:event-store:drop')
            ->setDescription('Drops the event store schema')
            ->setHelp(
                <<<'EOT'
    The <info>%command.name%</info> command drops the schema in the default
    connections database:

    <info>php app/console %command.name%</info>
    EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->getSchemaManager();
        $table = $this->eventStore->configureTable(new Schema());

        if ($schemaManager->tablesExist([$table->getName()])) {
            $schemaManager->dropTable($table->getName());
            $output->writeln('<info>Dropped Broadway event-store schema</info>');
        } else {
            $output->writeln('<info>Broadway event-store schema does not exist</info>');
        }

        return 0;
    }
}
