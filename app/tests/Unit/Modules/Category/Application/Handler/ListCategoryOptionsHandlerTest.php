<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Handler\ListCategoryOptionsHandler;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ListCategoryOptionsHandlerTest extends TestCase
{
    public function testItReturnsOnlyActiveCategoriesAsOptions(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $active = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'SUB-14',
            new Name('Sub 14'),
            new MinimumAge(13),
            new MaximumAge(14),
            new Description('Categoria formativa'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );
        $inactive = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d91'),
            $academyId,
            'SUB-16',
            new Name('Sub 16'),
            new MinimumAge(15),
            new MaximumAge(16),
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );
        $inactive->inactivate('actor-id');

        $handler = new ListCategoryOptionsHandler(new ListCategoryOptionsInMemoryRepository($active, $inactive));
        $result = $handler($academyId);

        self::assertCount(1, $result);
        self::assertSame('SUB-14', $result[0]->toArray()['categoryKey']);
        self::assertSame(CategoryStatus::active()->value(), $result[0]->toArray()['status']);
    }
}

final class ListCategoryOptionsInMemoryRepository implements CategoryRepository
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
            if ($category->academyId()->value() === $academyId->value() && $category->id()->value() === $categoryId->value()) {
                return $category;
            }
        }

        return null;
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
                && $category->status()->value() === CategoryStatus::active()->value()
        ));
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
