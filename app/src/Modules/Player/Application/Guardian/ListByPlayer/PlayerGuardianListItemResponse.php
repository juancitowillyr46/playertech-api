<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\ListByPlayer;

use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;

final readonly class PlayerGuardianListItemResponse
{
    public function __construct(
        private string $id,
        private string $academyId,
        private string $playerId,
        private bool $isPrimary,
        private LegalGuardianResponse $guardian,
    ) {
    }

    public static function fromPlayerGuardian(PlayerGuardian $playerGuardian, LegalGuardianResponse $guardian): self
    {
        return new self(
            $playerGuardian->id()->value(),
            $playerGuardian->academyId()->value(),
            $playerGuardian->playerId()->value(),
            $playerGuardian->isPrimary(),
            $guardian,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'playerId' => $this->playerId,
            'isPrimary' => $this->isPrimary,
            'guardian' => $this->guardian->toArray(),
        ];
    }
}
