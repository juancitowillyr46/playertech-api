<?php
declare(strict_types=1);
namespace App\Shared\Application\Pagination;
final readonly class PaginatedResult
{
    public function __construct(public array $items, public PaginationMeta $meta) {}

    public static function fromItems(array $items, PaginationQuery $query, int $total): self
    {
        $totalPages = max(1, (int) ceil($total / $query->perPage));
        $page = min($query->page, $totalPages);

        return new self(
            $items,
            new PaginationMeta(
                $page,
                $query->perPage,
                $total,
                $totalPages,
                $page < $totalPages,
                $page > 1,
            )
        );
    }
}
