<?php
declare(strict_types=1);
namespace App\Shared\Application\Pagination;
final readonly class PaginationQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 20,
        public string $sort = 'created_at',
        public string $direction = 'DESC',
    ) {
    }
}
