<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

final readonly class CreateAcademyCommand
{
    public function __construct(
        public string $actorId,
        public array $payload,
    ) {
    }
}
