<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListGuardianOptionsQuery
{
    public function __construct(
        public AcademyId $academyId,
        public ?string $query = null,
    ) {
    }
}
