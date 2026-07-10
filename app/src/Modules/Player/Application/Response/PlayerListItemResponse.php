<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Response;

use App\Modules\Player\Domain\Player\Player;

final readonly class PlayerListItemResponse
{
    private function __construct(
        private string $id,
        private ?string $categoryId,
        private string $firstName,
        private string $lastName,
        private string $birthDate,
        private string $documentNumber,
        private string $status,
    ) {
    }

    public static function fromPlayer(Player $player): self
    {
        return new self(
            $player->id()->value(),
            $player->categoryId()?->value(),
            $player->firstName(),
            $player->lastName(),
            $player->birthDate()->format('Y-m-d'),
            $player->documentNumber(),
            $player->status()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'documentNumber' => $this->documentNumber,
            'status' => $this->status,
        ];
    }
}
