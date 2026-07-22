<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Shield\Delete;

final readonly class DeleteAcademyShieldCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
    ) {
    }
}
