<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Query;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListAcademiesQuery
{
    public function __construct(public PaginationQuery $pagination)
    {
    }
}
