<?php

declare(strict_types=1);

namespace App\Modules\Staff\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;

final readonly class ListStaffOptionsQuery
{
    public function __construct(
        public AcademyId $academyId,
        public ?string $role = null,
    ) {
    }
}
