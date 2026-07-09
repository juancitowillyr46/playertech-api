<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Command;

final readonly class AssignPlayerToTeamCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $playerId,
        public string $teamId,
        public string $startDate
    ) {
    }
}
