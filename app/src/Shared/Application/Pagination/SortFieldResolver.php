<?php

declare(strict_types=1);

namespace App\Shared\Application\Pagination;

final readonly class SortFieldResolver
{
    /**
     * @param array<string, string> $allowedSorts
     */
    public function __construct(
        private array $allowedSorts,
        private string $defaultSort,
    ) {
    }

    public function resolve(string $sort): string
    {
        $normalizedSort = strtolower(trim($sort));

        return $this->allowedSorts[$normalizedSort] ?? $this->defaultSort;
    }
}
