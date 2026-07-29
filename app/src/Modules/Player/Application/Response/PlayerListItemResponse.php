<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Player\Player;
use App\Shared\Application\Response\MediaResponse;
use App\Shared\Domain\ValueObject\Media;

final readonly class PlayerListItemResponse
{
    private function __construct(
        private string $id,
        private ?string $categoryId,
        private ?string $categoryName,
        private ?string $genderName,
        private int $age,
        private string $documentType,
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
        private ?string $email,
        private ?string $phone,
        private ?MediaResponse $photo,
        private ?string $createdAt,
        private string $status,
    ) {
    }

    public static function fromPlayer(Player $player): self
    {
        return new self(
            $player->id()->value(),
            $player->categoryId()?->value(),
            null,
            self::genderNameFrom($player->gender()),
            self::ageFrom($player->birthDate()),
            $player->documentType(),
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
            $player->email(),
            $player->phone(),
            self::buildPhotoResponse($player->photo()),
            $player->auditTrail()?->createdAt()->value()->format(DATE_ATOM),
            $player->status()->value(),
        );
    }

    public static function fromPlayerWithCategoryName(Player $player, ?string $categoryName): self
    {
        return new self(
            $player->id()->value(),
            $player->categoryId()?->value(),
            $categoryName,
            self::genderNameFrom($player->gender()),
            self::ageFrom($player->birthDate()),
            $player->documentType(),
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
            $player->email(),
            $player->phone(),
            self::buildPhotoResponse($player->photo()),
            $player->auditTrail()?->createdAt()->value()->format(DATE_ATOM),
            $player->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
            'genderName' => $this->genderName,
            'age' => $this->age,
            'documentType' => $this->documentType,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'documentNumber' => $this->documentNumber,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => $this->photo?->toArray(),
            'createdAt' => $this->createdAt,
            'status' => $this->status,
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

    private static function genderNameFrom(?string $gender): ?string
    {
        if (null === $gender) {
            return null;
        }

        $normalized = mb_strtolower(trim($gender));

        return match ($normalized) {
            'm', 'masculino', 'male', 'hombre' => 'Masculino',
            'f', 'femenino', 'female', 'mujer' => 'Femenino',
            default => $gender,
        };
    }

    private static function ageFrom(\DateTimeImmutable $birthDate): int
    {
        return (int) $birthDate->diff(new \DateTimeImmutable('today'))->y;
    }
}
