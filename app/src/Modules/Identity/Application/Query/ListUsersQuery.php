<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Query;

final readonly class ListUsersQuery
{
    public function __construct(
        public ?string $academyId = null,
    ) {
    }
}
