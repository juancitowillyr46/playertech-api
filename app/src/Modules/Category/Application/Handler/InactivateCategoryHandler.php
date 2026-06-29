<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\InactivateCategoryCommand;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;

final readonly class InactivateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFinder $categoryFinder
    ) {
    }

    public function __invoke(InactivateCategoryCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);

        $categoryId = new CategoryId($command->categoryId);

        $category = $this->categoryFinder->findOrFail($academyId, $categoryId);

        $category->inactivate($command->actorId);

        $this->categoryRepository->save($category);
    }

    // private function requireCategory(
    //     string $academyId,
    //     string $categoryId,
    // ): Category {
    //     if (
    //         !Uuid::isValid($academyId)
    //         || !Uuid::isValid($categoryId)
    //     ) {
    //         throw new NotFoundHttpException('Category not found.');
    //     }

    //     $category = $this->categoryRepository->findById(
    //         new AcademyId($academyId),
    //         new CategoryId($categoryId),
    //     );

    //     if (null === $category) {
    //         throw new NotFoundHttpException('Category not found.');
    //     }

    //     return $category;
    // }
}