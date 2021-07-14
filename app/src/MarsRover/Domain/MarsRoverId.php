<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use Assert\Assertion as Assert;

final class MarsRoverId
{
    private string $id;

    public function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
