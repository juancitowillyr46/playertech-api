<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Player\ListByGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListGuardianPlayersQuery
{
    public function __construct(
        public AcademyId $academyId,
        public LegalGuardianId $guardianId,
        public PaginationQuery $pagination,
    ) {
    }
}
