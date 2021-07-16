<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use App\MarsRover\Application\Command\CreateMarsRover;
use App\MarsRover\Application\Command\MoveBackwardMarsRover;
use App\MarsRover\Application\Command\MoveForwardMarsRover;
use App\MarsRover\Domain\Event\MarsRoverWasCreated;
use App\MarsRover\Domain\Event\MarsRoverWasMovedBackward;
use App\MarsRover\Domain\Event\MarsRoverWasMovedForward;

class MarsRoverTest extends MarsRoverCommandHandlerTest
{
    /**
     * @test
     */
    public function it_creates_a_mars_rover(): void
    {
        $id = new MarsRoverId('00000000-0000-0000-0000-000000000000');
        $name = 'Dummy';
        $position = Position::create(1, 1);
        $orientation = Orientation::north();

        $this->scenario
            ->given([])
            ->when(new CreateMarsRover($id, $name, $position, $orientation))
            ->then([
                new MarsRoverWasCreated($id, $name, $position, $orientation),
            ]);
    }

    /**
     * @test
     */
    public function it_moves_forward_a_mars_rover_when_orientation_is_north(): void
    {
        $id = new MarsRoverId('00000000-0000-0000-0000-000000000000');
        $name = 'Dummy';
        $position = Position::create(1, 1);
        $orientation = Orientation::north();

        $this->scenario
            ->withAggregateId((string) $id)
            ->given([new MarsRoverWasCreated($id, $name, $position, $orientation)])
            ->when(new MoveForwardMarsRover($id))
            ->then([
                new MarsRoverWasMovedForward($id, 0, 1),
            ]);
    }

    /**
     * @test
     */
    public function it_moves_backward_a_mars_rover_when_orientation_is_north(): void
    {
        $id = new MarsRoverId('00000000-0000-0000-0000-000000000000');
        $name = 'Dummy';
        $position = Position::create(1, 1);
        $orientation = Orientation::north();

        $this->scenario
            ->withAggregateId((string) $id)
            ->given([new MarsRoverWasCreated($id, $name, $position, $orientation)])
            ->when(new MoveBackwardMarsRover($id))
            ->then([
                new MarsRoverWasMovedBackward($id, 0, -1),
            ]);
    }
}
