<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\ChangePrimary;

final readonly class ChangePrimaryGuardianResponse
{
    public function __construct(
        private string $id,
        private string $academyId,
        private string $playerId,
        private string $guardianId,
        private bool $isPrimary,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'academyId' => $this->academyId,
            'playerId' => $this->playerId,
            'guardianId' => $this->guardianId,
            'isPrimary' => $this->isPrimary,
        ];
    }
}
