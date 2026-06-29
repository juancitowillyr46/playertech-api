<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListPlayersQuery
{
    public function __construct(
        public AcademyId $academyId,
    ) {
    }
}
