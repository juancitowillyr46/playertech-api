<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\UpdateCategoryCommand;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Application\Services\CategoryKeyGenerator;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryAlreadyExistsException;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;

final readonly class UpdateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFinder $categoryFinder,
        private CategoryKeyGenerator $categoryKeyGenerator,
    ) {
    }

    public function __invoke(UpdateCategoryCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);
        $categoryId = new CategoryId($command->categoryId);
        $category = $this->categoryFinder->findOrFail($academyId, $categoryId);

        $categoryKey = $this->categoryKeyGenerator->generate($command->input->name);
        $existing = $this->categoryRepository->findByCategoryKey($academyId, $categoryKey);

        if (null !== $existing && $existing->id()->value() !== $categoryId->value()) {
            throw new CategoryAlreadyExistsException();
        }

        $category->update(
            $categoryKey,
            new Name($command->input->name),
            new MinimumAge($command->input->minAge),
            new MaximumAge($command->input->maxAge),
            null === $command->input->description ? null : new Description($command->input->description),
            $command->actorId,
        );

        $this->categoryRepository->save($category);
    }
}
