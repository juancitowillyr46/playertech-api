<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Command;

use App\Modules\Player\Application\Dto\UpdatePlayerInput;

final readonly class UpdatePlayerCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $playerId,
        public UpdatePlayerInput $input,
    ) {
    }
}
