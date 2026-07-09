<?php
declare(strict_types=1);
namespace App\Shared\Application\Pagination;
final readonly class PaginationMeta
{
    public function __construct(
        public int $page,
        public int $perPage,
        public int $total,
        public int $totalPages,
        public bool $hasNext,
        public bool $hasPrev,
    ) {
    }
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'total_pages' => $this->totalPages,
            'has_next' => $this->hasNext,
            'has_prev' => $this->hasPrev,
        ];
    }
}
