<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

final readonly class ResendUserInvitationCommand
{
    public function __construct(
        public string $actorId,
        public string $userId,
        public string $academyId,
    ) {
    }
}
