<?php

declare(strict_types=1);

namespace App\MarsRover\Application\Command;

use App\MarsRover\Domain\MarsRoverId;
use App\MarsRover\Domain\Orientation;
use App\MarsRover\Domain\Position;

final class CreateMarsRover
{
    private MarsRoverId $id;
    private string $name;
    private Position $position;
    private Orientation $orientation;

    public function __construct(MarsRoverId $id, string $name, Position $position, Orientation $orientation)
    {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->orientation = $orientation;
    }

    public function getId(): MarsRoverId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getOrientation(): Orientation
    {
        return $this->orientation;
    }
}
