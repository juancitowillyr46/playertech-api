<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Command;

use App\Modules\Team\Application\Dto\UpdateTeamInput;

final readonly class UpdateTeamCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $teamId,
        public UpdateTeamInput $input,
    ) {
    }
}
