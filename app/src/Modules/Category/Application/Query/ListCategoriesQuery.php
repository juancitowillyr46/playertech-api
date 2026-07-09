<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListCategoriesQuery
{
    public function __construct(
        public AcademyId $academyId,
        public PaginationQuery $pagination,
    ) {
    }
}
