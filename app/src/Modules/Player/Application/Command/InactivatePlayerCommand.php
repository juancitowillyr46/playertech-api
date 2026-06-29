<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Command;

final readonly class InactivatePlayerCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $playerId,
    ) {
    }
}
