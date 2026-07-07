<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Command;

use App\Modules\Team\Application\Dto\CreateTeamInput;

final readonly class CreateTeamCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public CreateTeamInput $input,
    ) {
    }
}
