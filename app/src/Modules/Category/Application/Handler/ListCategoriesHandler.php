<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Query\ListCategoriesQuery;
use App\Modules\Category\Application\Response\CategoryListItemResponse;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListCategoriesHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return CategoryListItemResponse[]
     */
    public function __invoke(ListCategoriesQuery $query): PaginatedResult
    {
        $categories = $this->categoryRepository->findAllByAcademy(
            $query->academyId,
            $query->pagination
        );

        $items = array_map(
            static fn ($category) => CategoryListItemResponse::fromCategory($category),
            $categories['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $categories['total']);
    }
}
