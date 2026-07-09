<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Query;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListUsersQuery
{
    public function __construct(
        public ?string $academyId = null,
        public PaginationQuery $pagination = new PaginationQuery(),
    ) {
    }
}
