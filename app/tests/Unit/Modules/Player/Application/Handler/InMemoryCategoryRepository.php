<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;

final class InMemoryCategoryRepository implements CategoryRepository
{
    /** @var array<string, Category> */
    public array $categories = [];

    public function save(Category $category): void
    {
        $this->categories[$category->id()->value()] = $category;
    }

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->academyId()->equals($academyId) && $category->id()->equals($categoryId)) {
                return $category;
            }
        }

        return null;
    }

    public function findAllByAcademy(AcademyId $academyId): array
    {
        return array_values(array_filter(
            $this->categories,
            static fn (Category $category): bool => $category->academyId()->equals($academyId)
        ));
    }
}
