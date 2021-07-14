<?php

declare(strict_types=1);

namespace App\MarsRover\Application\Command;

use App\MarsRover\Domain\MarsRover;
use App\MarsRover\Domain\MarsRoverRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class MarsRoverCommandHandler extends SimpleCommandHandler
{
    private MarsRoverRepository $repository;

    public function __construct(MarsRoverRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handleCreateMarsRover(CreateMarsRover $command): void
    {
        $marsRover = MarsRover::create(
            $command->getId(),
            $command->getName(),
            $command->getPosition(),
            $command->getOrientation()
        );

        $this->repository->save($marsRover);
    }
}
