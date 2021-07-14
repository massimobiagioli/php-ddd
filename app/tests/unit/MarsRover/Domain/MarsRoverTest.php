<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use PHPUnit\Framework\TestCase;

class MarsRoverTest extends TestCase
{
    public function test_it_creates_new_mars_rover(): void
    {
        $marsRover = new MarsRover();

        self::assertNotNull($marsRover);
    }
}
