<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use App\MarsRover\Domain\Event\MarsRoverWasCreated;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class MarsRover extends EventSourcedAggregateRoot
{
    private MarsRoverId $id;
    private string $name;
    private Position $position;
    private Orientation $orientation;

    private function __construct(MarsRoverId $id, string $name, Position $position, Orientation $orientation)
    {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->orientation = $orientation;

        $this->apply(
            new MarsRoverWasCreated($id, $name, $position, $orientation)
        );
    }

    public function getAggregateRootId(): string
    {
        return (string) $this->id;
    }

    public static function create(MarsRoverId $id, string $name, Position $position, Orientation $orientation): self
    {
        return new self($id, $name, $position, $orientation);
    }

    protected function applyMarsRoverWasCreated(MarsRoverWasCreated $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->position = $event->getPosition();
        $this->orientation = $event->getOrientation();
    }
}
