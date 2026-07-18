<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Category;

interface OnboardingCategoryRepository
{
    /**
     * @return OnboardingCategory[]
     */
    public function findAllActive(): array;

    public function findById(string $id): ?OnboardingCategory;
}
