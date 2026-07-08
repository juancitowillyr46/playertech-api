<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\Associate;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class AssociateGuardianCommand
{
    public function __construct(
        public string $actorId,
        public AcademyId $academyId,
        public PlayerId $playerId,
        public AssociateGuardianInput $input,
    ) {
    }
}
