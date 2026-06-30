<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Category;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\Name;

interface CategoryRepository
{
    public function save(Category $category): void;

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category;

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category;

    /**
     * @return Category[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;

    // public function findOneByAcademyAndName(
    //     AcademyId $academyId,
    //     Name $name
    // ): ?Category;
}
