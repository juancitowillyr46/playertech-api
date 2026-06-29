<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryNotFoundException;
use App\Shared\Domain\Exception\IdInvalidException;
use Symfony\Component\Uid\Uuid;

final readonly class CategoryFinder
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function findOrFail(
        AcademyId $academyId,
        CategoryId $categoryId
    ): Category {

        if (!Uuid::isValid($academyId->value()) || !Uuid::isValid($categoryId->value())) {
            throw new IdInvalidException();
        }

        $category = $this->categoryRepository->findById(
            $academyId,
            $categoryId
        );

        if ($category === null) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }
}
