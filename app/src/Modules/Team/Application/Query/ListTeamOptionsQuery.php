<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListTeamOptionsQuery
{
    public function __construct(
        public AcademyId $academyId,
        public ?string $query = null,
    ) {
    }
}
