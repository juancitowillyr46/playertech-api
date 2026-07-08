<?php

declare(strict_types=1);

namespace App\Modules\Membership\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;

final readonly class ShowActiveMembershipQuery
{
    public function __construct(
        public AcademyId $academyId,
        public PlayerId $playerId,
    ) {
    }
}
