<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Query\ShowCategoryQuery;
use App\Modules\Category\Application\Response\CategoryResponse;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryNotFoundException;

final readonly class ShowCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(
        ShowCategoryQuery $query,
    ): CategoryResponse {
        $category = $this->categoryRepository->findById(
            $query->academyId,
            $query->categoryId,
        );

        if (null === $category) {
            throw new CategoryNotFoundException();
        }

        return CategoryResponse::fromCategory($category);
    }
}