<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use App\MarsRover\Application\Command\CreateMarsRover;
use App\MarsRover\Domain\Event\MarsRoverWasCreated;

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
}
