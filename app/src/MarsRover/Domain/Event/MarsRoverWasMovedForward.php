<?php

declare(strict_types=1);

namespace App\MarsRover\Domain\Event;

use App\MarsRover\Domain\MarsRoverId;

final class MarsRoverWasMovedForward
{
    private MarsRoverId $id;
    private int $xOffset;
    private int $yOffset;

    public function __construct(MarsRoverId $id, int $xOffset, int $yOffset)
    {
        $this->id = $id;
        $this->xOffset = $xOffset;
        $this->yOffset = $yOffset;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            new MarsRoverId($data['id'], $data['xOffset'], $data['yOffset']),
        );
    }

    public function serialize(): array
    {
        return [
            'id' => (string) $this->getId(),
            'xOffset' => $this->xOffset,
            'yOffset' => $this->yOffset
        ];
    }

    public function getId(): MarsRoverId
    {
        return $this->id;
    }

    public function getXOffset(): int
    {
        return $this->xOffset;
    }

    public function getYOffset(): int
    {
        return $this->yOffset;
    }
}
