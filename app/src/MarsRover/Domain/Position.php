<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializable;

final class Position implements Serializable
{
    private const MAX_X = 10;
    private const MAX_Y = 10;

    private int $x;
    private int $y;

    private function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function create(int $x, int $y): self
    {
        Assert::between($x, 0, self::MAX_X);
        Assert::between($y, 0, self::MAX_Y);

        return new self($x, $y);
    }

    public static function deserialize(array $data): self
    {
        return self::create((int) $data['x'], (int) $data['y']);
    }

    public function serialize(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
        ];
    }
}
