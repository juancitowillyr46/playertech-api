<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Player\Player;
use App\Shared\Application\Response\MediaResponse;

final readonly class PlayerResponse
{
    private function __construct(
        private string $id,
        private string $academyId,
        private ?string $categoryId,
        private string $documentType,
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
        private ?string $email,
        private ?string $phone,
        private ?string $nationality,
        private ?string $gender,
        private ?string $federationId,
        private ?string $dominantFoot,
        private ?MediaResponse $photo,
        private string $status,
    ) {
    }

    public static function fromPlayer(Player $player): self
    {
        return new self(
            $player->id()->value(),
            $player->academyId()->value(),
            $player->categoryId()?->value(),
            $player->documentType(),
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
            $player->email(),
            $player->phone(),
            $player->nationality(),
            $player->gender(),
            $player->federationId(),
            $player->dominantFoot(),
            null === $player->photo() ? null : MediaResponse::fromDetails(
                $player->photo()->path(),
                $player->photo()->url(),
                $player->photo()->mimeType(),
                $player->photo()->size(),
                $player->photo()->checksum(),
            ),
            $player->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'categoryId' => $this->categoryId,
            'documentType' => $this->documentType,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'documentNumber' => $this->documentNumber,
            'email' => $this->email,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'federationId' => $this->federationId,
            'dominantFoot' => $this->dominantFoot,
            'photo' => $this->photo?->toArray(),
            'status' => $this->status,
        ];
    }
}
