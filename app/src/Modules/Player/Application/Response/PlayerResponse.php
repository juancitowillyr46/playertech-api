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
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
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
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
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
            'academy_id' => $this->academyId,
            'category_id' => $this->categoryId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'birth_date' => $this->birthDate,
            'document_number' => $this->documentNumber,
            'photo' => $this->photo?->toArray(),
            'status' => $this->status,
        ];
    }
}
