<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Player\Player;
use App\Shared\Application\Response\MediaResponse;
use App\Shared\Domain\ValueObject\Media;

final readonly class PlayerSummaryResponse
{
    private function __construct(
        private string $firstName,
        private string $lastName,
        private ?MediaResponse $photo,
        private ?string $gender,
    ) {
    }

    public static function fromPlayer(Player $player): self
    {
        return new self(
            $player->firstName(),
            $player->lastName(),
            self::buildPhotoResponse($player->photo()),
            $player->gender(),
        );
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'photo' => $this->photo?->toArray(),
            'gender' => $this->gender,
        ];
    }

    private static function buildPhotoResponse(?Media $photo): ?MediaResponse
    {
        if (null === $photo) {
            return null;
        }

        $reflection = new \ReflectionObject($photo);
        foreach (['path', 'url', 'mimeType', 'size', 'checksum'] as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if (!$property->isInitialized($photo)) {
                return null;
            }
        }

        return MediaResponse::fromDetails(
            $photo->path(),
            $photo->url(),
            $photo->mimeType(),
            $photo->size(),
            $photo->checksum(),
        );
    }
}
