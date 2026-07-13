<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;

final readonly class ShowLegalGuardianQuery
{
    public function __construct(
        public AcademyId $academyId,
        public LegalGuardianId $guardianId,
    ) {
    }
}
