<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\RemoveAssociation;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class RemoveGuardianAssociationCommand
{
    public function __construct(
        public string $actorId,
        public AcademyId $academyId,
        public PlayerId $playerId,
        public LegalGuardianId $guardianId,
    ) {
    }
}
