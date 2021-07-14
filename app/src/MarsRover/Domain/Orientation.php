<?php

declare(strict_types=1);

namespace App\MarsRover\Domain;

use Assert\Assertion as Assert;

final class Orientation
{
    private const NORTH = 'N';
    private const SOUTH = 'S';
    private const EAST = 'E';
    private const WEST = 'W';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function north(): self
    {
        return new self(self::NORTH);
    }

    public static function south(): self
    {
        return new self(self::SOUTH);
    }

    public static function east(): self
    {
        return new self(self::EAST);
    }

    public static function west(): self
    {
        return new self(self::WEST);
    }

    public static function fromString(string $value): self
    {
        Assert::inArray($value, [self::NORTH, self::SOUTH, self::EAST, self::WEST]);

        return new self($value);
    }
}
