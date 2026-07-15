<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Query;

final readonly class ShowAcademyTaxProfileQuery
{
    public function __construct(
        public string $academyId,
    ) {
    }
}
