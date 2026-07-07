<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Team\Domain\Team\TeamId;

final readonly class ShowTeamQuery
{
    public function __construct(
        public AcademyId $academyId,
        public TeamId $teamId,
    ) {
    }
}
