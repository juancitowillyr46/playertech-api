<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListVenuesQuery
{
    public function __construct(
        public AcademyId $academyId,
    ) {
    }
}