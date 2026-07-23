<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Response\CategoryOptionResponse;
use App\Modules\Category\Domain\Category\CategoryRepository;

final readonly class ListCategoryOptionsHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return CategoryOptionResponse[]
     */
    public function __invoke(AcademyId $academyId): array
    {
        $categories = $this->categoryRepository->findActiveByAcademy($academyId);

        return array_map(
            static fn ($category) => CategoryOptionResponse::fromCategory($category),
            $categories
        );
    }
}
