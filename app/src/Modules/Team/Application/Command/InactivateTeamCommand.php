<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Command;

final readonly class InactivateTeamCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $teamId,
    ) {
    }
}
