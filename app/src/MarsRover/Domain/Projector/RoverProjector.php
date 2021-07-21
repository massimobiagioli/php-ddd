<?php

declare(strict_types=1);

namespace App\MarsRover\Model\Projector;

use App\MarsRover\Domain\Event\MarsRoverWasCreated;
use App\MarsRover\Domain\Event\MarsRoverWasMovedBackward;
use App\MarsRover\Domain\Event\MarsRoverWasMovedForward;
use App\MarsRover\Domain\Event\MarsRoverWasTurnedLeft;
use App\MarsRover\Domain\Event\MarsRoverWasTurnedRight;
use App\MarsRover\Model\ReadModel\Rover;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Repository;

/**
 * Class RoverProjector
 * CRUD Projector.
 */
class RoverProjector extends Projector
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    protected function applyMarsRoverWasCreated(MarsRoverWasCreated $event): void
    {
        $readModel = $this->getReadModel((string) $event->getId());
        $readModel->init($event->getName(), $event->getPosition(), $event->getOrientation());
        $this->repository->save($readModel);
    }

    protected function applyMarsRoverWasMovedForward(MarsRoverWasMovedForward $event): void
    {
        $readModel = $this->getReadModel((string) $event->getId());
        $readModel->changePosition($event->getXOffset(), $event->getYOffset());
        $readModel->incOdometer();
        $this->repository->save($readModel);
    }

    protected function applyMarsRoverWasMovedBackward(MarsRoverWasMovedBackward $event): void
    {
        $readModel = $this->getReadModel((string) $event->getId());
        $readModel->changePosition($event->getXOffset(), $event->getYOffset());
        $readModel->incOdometer();
        $this->repository->save($readModel);
    }

    protected function applyMarsRoverWasTurnedLeft(MarsRoverWasTurnedLeft $event): void
    {
        $readModel = $this->getReadModel((string) $event->getId());
        $readModel->changeOrientation($event->getNewOrientation());
        $readModel->incSwerves();
        $this->repository->save($readModel);
    }

    protected function applyMarsRoverWasTurnedRight(MarsRoverWasTurnedRight $event): void
    {
        $readModel = $this->getReadModel((string) $event->getId());
        $readModel->changeOrientation($event->getNewOrientation());
        $readModel->incSwerves();
        $this->repository->save($readModel);
    }

    private function getReadModel(string $id): Rover
    {
        $readModel = $this->repository->find($id);
        if (null === $readModel) {
            $readModel = new Rover($id);
        }

        return $readModel;
    }
}
