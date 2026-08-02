<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Command;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class InactivateLegalGuardianCommand
{
    public function __construct(
        public string $actorId,
        public AcademyId $academyId,
        public string $guardianId,
    ) {
    }
}
