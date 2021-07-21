<?php

declare(strict_types=1);

namespace App\MarsRover\Domain\Event;

use App\MarsRover\Domain\MarsRoverId;
use App\MarsRover\Domain\Orientation;
use Broadway\Serializer\Serializable;

final class MarsRoverWasTurnedLeft implements Serializable
{
    private MarsRoverId $id;
    private Orientation $newOrientation;

    public function __construct(MarsRoverId $id, Orientation $newOrientation)
    {
        $this->id = $id;
        $this->newOrientation = $newOrientation;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            new MarsRoverId($data['id']),
            Orientation::fromString($data['newOrientation']),
        );
    }

    public function serialize(): array
    {
        return [
            'id' => (string) $this->getId(),
            'newOrientation' => (string) $this->newOrientation,
        ];
    }

    public function getId(): MarsRoverId
    {
        return $this->id;
    }

    public function getNewOrientation(): Orientation
    {
        return $this->newOrientation;
    }
}
