<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Command;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Dto\UpdateLegalGuardianInput;

final readonly class UpdateLegalGuardianCommand
{
    public function __construct(
        public string $actorId,
        public AcademyId $academyId,
        public string $guardianId,
        public UpdateLegalGuardianInput $input,
    ) {
    }
}
