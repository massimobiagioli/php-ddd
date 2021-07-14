<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use App\MarsRover\Application\Command\MarsRoverCommandHandler;
use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

abstract class MarsRoverCommandHandlerTest extends CommandHandlerScenarioTestCase
{
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new MarsRoverCommandHandler(
            new MarsRoverRepository($eventStore, $eventBus)
        );
    }
}
