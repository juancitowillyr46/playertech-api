<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\UpdateCategoryCommand;
use App\Modules\Category\Application\Response\CategoryResponse;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryAlreadyExistsException;
use App\Modules\Category\Domain\Exception\CategoryNotFoundException;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;

final readonly class UpdateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(UpdateCategoryCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);

        $categoryId = new CategoryId($command->categoryId);

        $category = $this->categoryRepository->findById($academyId, $categoryId);

        if (null === $category) {
            throw new CategoryNotFoundException();
        }

        $category->update(
            new Name($command->input->name),
            new MinimumAge($command->input->minAge),
            new MaximumAge($command->input->maxAge),
            new Description($command->input->description),
            $command->actorId,
        );

        $this->categoryRepository->save($category);
        
    }
}