<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;


use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\ActivateCategoryCommand;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;

final readonly class ActivateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFinder $categoryFinder
    ) {
    }

    public function __invoke(ActivateCategoryCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);

        $categoryId = new CategoryId($command->categoryId);

        $category = $this->categoryFinder->findOrFail($academyId, $categoryId);

        $category->activate($command->actorId);

        $this->categoryRepository->save($category);
    }
}