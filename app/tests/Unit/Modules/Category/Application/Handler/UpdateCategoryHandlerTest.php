<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\UpdateCategoryCommand;
use App\Modules\Category\Application\Dto\UpdateCategoryInput;
use App\Modules\Category\Application\Handler\UpdateCategoryHandler;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Exception\CategoryAlreadyExistsException;
use App\Modules\Category\Application\Services\CategoryKeyGenerator;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class UpdateCategoryHandlerTest extends TestCase
{
    public function testItUpdatesCategoryWithoutCollidingWithItsOwnKey(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $category = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'SUB-14',
            new Name('Sub 14'),
            new MinimumAge(13),
            new MaximumAge(14),
            new Description('Categoria formativa'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );

        $repository = new UpdateCategoryInMemoryRepository($category);
        $handler = new UpdateCategoryHandler(
            $repository,
            new \App\Modules\Category\Application\Services\CategoryFinder($repository),
            new CategoryKeyGenerator()
        );

        $response = $handler(new UpdateCategoryCommand(
            'actor-id',
            $academyId->value(),
            $category->id()->value(),
            new UpdateCategoryInput(
                'Sub 14 Renombrada',
                12,
                15,
                'Categoria renovada',
            )
        ));

        self::assertNull($response);
        self::assertSame('SUB-14-RENOMBRADA', $repository->findById($academyId, $category->id())->categoryKey());
        self::assertSame('Sub 14 Renombrada', $repository->findById($academyId, $category->id())->name()->value());
        self::assertSame(12, $repository->findById($academyId, $category->id())->minAge()->value());
        self::assertSame(15, $repository->findById($academyId, $category->id())->maxAge()->value());
        self::assertSame('Categoria renovada', $repository->findById($academyId, $category->id())->description()?->value());
    }

    public function testItRejectsARepeatedKeyFromAnotherCategory(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $current = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'SUB-14',
            new Name('Sub 14'),
            new MinimumAge(13),
            new MaximumAge(14),
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );
        $duplicate = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d91'),
            $academyId,
            'SUB-16',
            new Name('Sub 16'),
            new MinimumAge(15),
            new MaximumAge(16),
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );

        $repository = new UpdateCategoryInMemoryRepository($current, $duplicate);
        $handler = new UpdateCategoryHandler(
            $repository,
            new \App\Modules\Category\Application\Services\CategoryFinder($repository),
            new CategoryKeyGenerator()
        );

        $this->expectException(CategoryAlreadyExistsException::class);

        $handler(new UpdateCategoryCommand(
            'actor-id',
            $academyId->value(),
            $current->id()->value(),
            new UpdateCategoryInput(
                'Sub 16',
                12,
                15,
                null,
            )
        ));
    }
}

final class UpdateCategoryInMemoryRepository implements CategoryRepository
{
    /** @var array<string, Category> */
    private array $items = [];

    public function __construct(Category ...$categories)
    {
        foreach ($categories as $category) {
            $this->items[$category->id()->value()] = $category;
        }
    }

    public function save(Category $category): void
    {
        $this->items[$category->id()->value()] = $category;
    }

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        $category = $this->items[$categoryId->value()] ?? null;

        if (null === $category || $category->academyId()->value() !== $academyId->value()) {
            return null;
        }

        return $category;
    }

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
    {
        $normalizedKey = strtoupper(trim($categoryKey));

        foreach ($this->items as $category) {
            if ($category->academyId()->value() === $academyId->value() && $category->categoryKey() === $normalizedKey) {
                return $category;
            }
        }

        return null;
    }

    public function findActiveByAcademy(AcademyId $academyId): array
    {
        return array_values(array_filter(
            $this->items,
            static fn (Category $category): bool => $category->academyId()->value() === $academyId->value()
                && $category->status()->value() === \App\Modules\Category\Domain\Category\CategoryStatus::active()->value()
        ));
    }

    public function findActiveOptionsByAcademy(AcademyId $academyId): array
    {
        return array_map(
            static fn (Category $category): array => [
                'id' => $category->id()->value(),
                'categoryKey' => $category->categoryKey(),
                'name' => $category->name()->value(),
                'status' => $category->status()->value(),
            ],
            $this->findActiveByAcademy($academyId)
        );
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        $items = array_values(array_filter(
            $this->items,
            static fn (Category $category): bool => $category->academyId()->value() === $academyId->value()
        ));

        return [
            'items' => $items,
            'total' => count($items),
        ];
    }
}
