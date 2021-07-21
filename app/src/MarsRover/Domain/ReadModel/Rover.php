<?php

declare(strict_types=1);

namespace App\MarsRover\Model\ReadModel;

use App\MarsRover\Domain\Orientation;
use App\MarsRover\Domain\Position;
use Broadway\ReadModel\SerializableReadModel;

class Rover implements SerializableReadModel
{
    private string $id;
    private array $metadata = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'metadata' => $this->metadata,
        ];
    }

    public static function deserialize(array $data): self
    {
        $readModel = new self($data['id']);
        $readModel->metadata = $data['metadata'];

        return $readModel;
    }

    public function init(string $name, Position $position, Orientation $orientation): void
    {
        $this->metadata['name'] = $name;
        $this->metadata['position'] = $position->serialize();
        $this->metadata['orientation'] = (string) $orientation;
        $this->metadata['stats'] = [
            'odometer' => 0,
            'swerves' => 0
        ];
    }

    public function changePosition($xOffset, $yOffset): void
    {
        $position = Position::deserialize($this->metadata['position']);
        $position->applyXOffset($xOffset);
        $position->applyYOffset($yOffset);
        $this->metadata['position'] = $position->serialize();
    }

    public function changeOrientation(Orientation $newOrientation): void
    {
        $this->metadata['orientation'] = (string) $newOrientation;
    }

    public function incOdometer(): void
    {
        $this->metadata['stats']['odometer']++;
    }

    public function incSwerves(): void
    {
        $this->metadata['stats']['swerves']++;
    }

    public function getName(): string
    {
        return $this->metadata['name'];
    }

    public function getPosition(): Position
    {
        return Position::deserialize($this->metadata['position']);
    }

    public function getOrientation(): Orientation
    {
        return Orientation::fromString($this->metadata['orientation']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'position' => $this->getPosition()->serialize(),
            'orientation' => $this->getOrientation(),
            'stats' => $this->metadata['stats']
        ];
    }
}
