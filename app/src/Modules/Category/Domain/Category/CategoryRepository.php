<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Category;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Pagination\PaginationQuery;

interface CategoryRepository
{
    public function save(Category $category): void;

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category;

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category;

    /**
     * @return Category[]
     */
    public function findActiveByAcademy(AcademyId $academyId): array;

    /**
     * @return array{items: Category[], total: int}
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;

    // public function findOneByAcademyAndName(
    //     AcademyId $academyId,
    //     Name $name
    // ): ?Category;
}
