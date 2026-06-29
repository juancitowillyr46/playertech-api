<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Application\Query\ShowCategoryQuery;
use App\Modules\Category\Application\Response\CategoryResponse;
use App\Modules\Category\Domain\Category\CategoryRepository;

final readonly class ShowCategoryHandler
{
    public function __construct(
        private CategoryFinder $categoryFinder,
    ) {
    }

    public function __invoke(
        ShowCategoryQuery $query,
    ): CategoryResponse {
        $category = $this->categoryFinder->findOrFail(
            $query->academyId,
            $query->categoryId,
        );

        return CategoryResponse::fromCategory($category);
    }
}
