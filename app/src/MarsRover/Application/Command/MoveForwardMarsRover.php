<?php

declare(strict_types=1);

namespace App\MarsRover\Application\Command;

use App\MarsRover\Domain\MarsRoverId;

final class MoveForwardMarsRover
{
    private MarsRoverId $id;

    public function __construct(MarsRoverId $id)
    {
        $this->id = $id;
    }

    public function getId(): MarsRoverId
    {
        return $this->id;
    }
}
