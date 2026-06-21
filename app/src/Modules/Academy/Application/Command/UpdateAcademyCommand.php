<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

final readonly class UpdateAcademyCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public array $payload,
    ) {
    }
}
