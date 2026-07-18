<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Response\OnboardingCategoryResponse;
use App\Modules\Category\Domain\Category\OnboardingCategoryRepository;

final readonly class ListOnboardingCategoriesHandler
{
    public function __construct(
        private OnboardingCategoryRepository $categoryRepository,
    ) {
    }

    /**
     * @return OnboardingCategoryResponse[]
     */
    public function __invoke(): array
    {
        return array_map(
            static fn ($category) => OnboardingCategoryResponse::fromOnboardingCategory($category),
            $this->categoryRepository->findAllActive()
        );
    }
}
