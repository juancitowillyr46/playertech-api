<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Handler\ListCategoriesHandler;
use App\Modules\Category\Application\Query\ListCategoriesQuery;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ListCategoriesHandlerTest extends TestCase
{
    public function testItIncludesAcademyIdInTheListContract(): void
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

        $handler = new ListCategoriesHandler(new ListCategoriesInMemoryRepository($category));
        $result = $handler(new ListCategoriesQuery($academyId, new PaginationQuery()));

        self::assertSame($academyId->value(), $result->items[0]->toArray()['academyId']);
        self::assertSame('SUB-14', $result->items[0]->toArray()['categoryKey']);
    }
}

final class ListCategoriesInMemoryRepository implements CategoryRepository
{
    /** @var Category[] */
    private array $items = [];

    public function __construct(Category ...$categories)
    {
        foreach ($categories as $category) {
            $this->items[] = $category;
        }
    }

    public function save(Category $category): void
    {
        $this->items[] = $category;
    }

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        foreach ($this->items as $category) {
            if ($category->academyId()->equals($academyId) && $category->id()->equals($categoryId)) {
                return $category;
            }
        }

        return null;
    }

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
    {
        $normalizedKey = strtoupper(trim($categoryKey));

        foreach ($this->items as $category) {
            if ($category->academyId()->equals($academyId) && $category->categoryKey() === $normalizedKey) {
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

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $items = array_values(array_filter(
            $this->items,
            static fn (Category $category): bool => $category->academyId()->equals($academyId)
        ));

        return [
            'items' => $items,
            'total' => count($items),
        ];
    }
}
