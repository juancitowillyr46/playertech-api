<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\InviteUserInput;

final readonly class InviteUserCommand
{
    public function __construct(
        public string $actorId,
        public InviteUserInput $input,
        public string $academyId,
    ) {
    }
}
