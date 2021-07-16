<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use App\MarsRover\Domain\Event\MarsRoverWasCreated;
use App\MarsRover\Domain\Event\MarsRoverWasMovedBackward;
use App\MarsRover\Domain\Event\MarsRoverWasMovedForward;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class MarsRover extends EventSourcedAggregateRoot
{
    private MarsRoverId $id;
    private string $name;
    private Position $position;
    private Orientation $orientation;

    public function getAggregateRootId(): string
    {
        return (string) $this->id;
    }

    public static function create(MarsRoverId $id, string $name, Position $position, Orientation $orientation): self
    {
        $marsRover = new self();
        $marsRover->init($id, $name, $position, $orientation);

        return $marsRover;
    }

    public function moveForward(): void
    {
        $xOffset = 0;
        $yOffset = 0;

        switch ($this->orientation) {
            case Orientation::NORTH:
                $yOffset = 1;
                break;
            case Orientation::SOUTH:
                $yOffset = -1;
                break;
            case Orientation::EAST:
                $xOffset = 1;
                break;
            case Orientation::WEST:
                $xOffset = -1;
                break;
        }

        $this->apply(
            new MarsRoverWasMovedForward($this->id, $xOffset, $yOffset)
        );
    }

    public function moveBackward(): void
    {
        $xOffset = 0;
        $yOffset = 0;

        switch ($this->orientation) {
            case Orientation::NORTH:
                $yOffset = -1;
                break;
            case Orientation::SOUTH:
                $yOffset = 1;
                break;
            case Orientation::EAST:
                $xOffset = -1;
                break;
            case Orientation::WEST:
                $xOffset = 1;
                break;
        }

        $this->apply(
            new MarsRoverWasMovedBackward($this->id, $xOffset, $yOffset)
        );
    }

    protected function applyMarsRoverWasCreated(MarsRoverWasCreated $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->position = $event->getPosition();
        $this->orientation = $event->getOrientation();
    }

    protected function applyMarsRoverWasMovedForward(MarsRoverWasMovedForward $event): void
    {
        $this->changePosition($event->getXOffset(), $event->getYOffset());
    }

    protected function applyMarsRoverWasMovedBackward(MarsRoverWasMovedBackward $event): void
    {
        $this->changePosition($event->getXOffset(), $event->getYOffset());
    }

    private function changePosition($xOffset, $yOffset)
    {
        $this->position->applyXOffset($xOffset);
        $this->position->applyYOffset($yOffset);
    }

    private function init(MarsRoverId $id, string $name, Position $position, Orientation $orientation): void
    {
        $this->apply(
            new MarsRoverWasCreated($id, $name, $position, $orientation)
        );
    }
}
