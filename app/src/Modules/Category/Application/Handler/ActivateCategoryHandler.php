<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\ActivateCategoryCommand;
use App\Modules\Category\Application\Command\InactivateCategoryCommand;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class ActivateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(ActivateCategoryCommand $command): void
    {
        $category = $this->requireCategory(
            $command->academyId,
            $command->categoryId,
        );

        $category->activate($command->actorId);

        $this->categoryRepository->save($category);
    }

    private function requireCategory(
        string $academyId,
        string $categoryId,
    ): Category {
        if (
            !Uuid::isValid($academyId)
            || !Uuid::isValid($categoryId)
        ) {
            throw new NotFoundHttpException('Category not found.');
        }

        $category = $this->categoryRepository->findById(
            new AcademyId($academyId),
            new CategoryId($categoryId),
        );

        if (null === $category) {
            throw new NotFoundHttpException('Category not found.');
        }

        return $category;
    }
}