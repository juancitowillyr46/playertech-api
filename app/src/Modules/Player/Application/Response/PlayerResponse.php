<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Player\Player;
use App\Shared\Application\Response\MediaResponse;
use App\Shared\Domain\Document\DocumentType;
use App\Shared\Domain\DominantFoot\DominantFoot;
use App\Shared\Domain\Gender\Gender;
use App\Shared\Domain\Nationality\Nationality;
use App\Shared\Domain\ValueObject\Media;

final readonly class PlayerResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private ?string $categoryId,
        private ?string $categoryName,
        private string $documentType,
        private ?string $documentTypeName,
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
        private ?string $email,
        private ?string $phone,
        private ?string $nationality,
        private ?string $nationalityName,
        private ?string $gender,
        private ?string $genderName,
        private ?string $federationId,
        private ?string $dominantFoot,
        private ?string $dominantFootName,
        private ?array $legalGuardianMain,
        private ?array $teamMain,
        private ?MediaResponse $photo,
        private string $status,
    ) {
    }

    public static function fromPlayer(
        Player $player,
        ?string $categoryName = null,
        ?array $legalGuardianMain = null,
        ?array $teamMain = null,
    ): self
    {
        return new self(
            $player->id()->value(),
            $player->academyId()->value(),
            $player->categoryId()?->value(),
            $categoryName,
            $player->documentType(),
            self::documentTypeNameFrom($player->documentType()),
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
            $player->email(),
            $player->phone(),
            $player->nationality(),
            self::nationalityNameFrom($player->nationality()),
            $player->gender(),
            self::genderNameFrom($player->gender()),
            $player->federationId(),
            $player->dominantFoot(),
            self::dominantFootNameFrom($player->dominantFoot()),
            $legalGuardianMain,
            $teamMain,
            self::buildPhotoResponse($player->photo()),
            $player->status()->value(),
        );
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
            'documentType' => $this->documentType,
            'documentTypeName' => $this->documentTypeName,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'documentNumber' => $this->documentNumber,
            'email' => $this->email,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'nationalityName' => $this->nationalityName,
            'gender' => $this->gender,
            'genderName' => $this->genderName,
            'federationId' => $this->federationId,
            'dominantFoot' => $this->dominantFoot,
            'dominantFootName' => $this->dominantFootName,
            'legalGuardianMain' => $this->legalGuardianMain,
            'teamMain' => $this->teamMain,
            'photo' => $this->photo?->toArray(),
            'status' => $this->status,
        ];
    }

    private static function documentTypeNameFrom(?string $documentType): ?string
    {
        if (null === $documentType || '' === trim($documentType)) {
            return null;
        }

        return DocumentType::tryFrom(strtoupper(trim($documentType)))?->label() ?? $documentType;
    }

    private static function nationalityNameFrom(?string $nationality): ?string
    {
        if (null === $nationality || '' === trim($nationality)) {
            return null;
        }

        return Nationality::tryFrom(strtoupper(trim($nationality)))?->label() ?? $nationality;
    }

    private static function genderNameFrom(?string $gender): ?string
    {
        if (null === $gender || '' === trim($gender)) {
            return null;
        }

        return Gender::tryFrom(strtoupper(trim($gender)))?->label() ?? $gender;
    }

    private static function dominantFootNameFrom(?string $dominantFoot): ?string
    {
        if (null === $dominantFoot || '' === trim($dominantFoot)) {
            return null;
        }

        return DominantFoot::tryFrom(strtoupper(trim($dominantFoot)))?->label() ?? $dominantFoot;
    }
}
