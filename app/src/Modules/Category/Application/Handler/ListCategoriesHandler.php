<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Query\ListCategoriesQuery;
use App\Modules\Category\Application\Response\CategoryListItemResponse;
use App\Modules\Category\Domain\Category\CategoryRepository;

final readonly class ListCategoriesHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return CategoryListItemResponse[]
     */
    public function __invoke(ListCategoriesQuery $query): array
    {
        $categories = $this->categoryRepository->findAllByAcademy(
            $query->academyId
        );

        return array_map(
            static fn ($category) => CategoryListItemResponse::fromCategory($category),
            $categories
        );
    }
}