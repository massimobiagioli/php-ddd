<?php

declare(strict_types=1);

namespace App\MarsRover\Domain\Event;

use App\MarsRover\Domain\MarsRoverId;
use App\MarsRover\Domain\Orientation;
use App\MarsRover\Domain\Position;
use Broadway\Serializer\Serializable;

final class MarsRoverWasCreated implements Serializable
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

    public static function deserialize(array $data): self
    {
        return new self(
            new MarsRoverId($data['id']),
            $data['name'],
            Position::deserialize($data['position']),
            Orientation::fromString($data['orientation'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => (string) $this->getId(),
            'name' => $this->name,
            'position' => $this->position->serialize(),
            'orientation' => (string) $this->orientation,
        ];
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
