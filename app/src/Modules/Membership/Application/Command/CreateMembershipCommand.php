<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Command;

final readonly class CreateMembershipCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $playerId,
        public string $primaryGuardianId,
    ) {
    }
}
