<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Player\Options;

use App\Modules\Player\Domain\Player\Player;

final readonly class AvailablePlayerOptionResponse
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private ?string $categoryName,
    ) {
    }

    public static function fromPlayer(Player $player, ?string $categoryName): self
    {
        return new self(
            $player->id()->value(),
            $player->firstName(),
            $player->lastName(),
            $categoryName,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'categoryName' => $this->categoryName,
        ];
    }
}
