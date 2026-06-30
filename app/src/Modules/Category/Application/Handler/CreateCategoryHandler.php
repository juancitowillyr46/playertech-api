<?php

declare(strict_types=1);

namespace App\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\CreateCategoryCommand;
use App\Modules\Category\Application\Response\CategoryResponse;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryAlreadyExistsException;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
final readonly class CreateCategoryHandler
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function __invoke(
        CreateCategoryCommand $command
    ): CategoryResponse {

        $academyId = new AcademyId($command->academyId);
        $categoryKey = $command->input->categoryKey;
        $name = new Name($command->input->name);

        if (null !== $this->categoryRepository->findByCategoryKey($academyId, $categoryKey)) {
            throw new CategoryAlreadyExistsException();
        }

        $category = Category::create(
            CategoryId::generate(),
            $academyId,
            $categoryKey,
            $name,
            new MinimumAge($command->input->minAge),
            new MaximumAge($command->input->maxAge),
            null === $command->input->description
                ? null
                : new Description($command->input->description),
            AuditTrail::create($command->actorId),
        );

        $this->categoryRepository->save($category);

        return CategoryResponse::fromCategory($category);

    }
}
