<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Query;
use App\Shared\Application\Pagination\PaginationQuery;
final readonly class ListPendingChargesQuery
{
    public function __construct(public string $academyId, public PaginationQuery $pagination) {}
}
