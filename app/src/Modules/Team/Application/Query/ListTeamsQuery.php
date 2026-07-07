<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListTeamsQuery
{
    public function __construct(
        public AcademyId $academyId,
    ) {
    }
}
