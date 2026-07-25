<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Category\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Command\CreateCategoryCommand;
use App\Modules\Category\Application\Dto\CreateCategoryInput;
use App\Modules\Category\Application\Handler\CreateCategoryHandler;
use App\Modules\Category\Application\Services\CategoryKeyGenerator;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Category\Domain\Exception\CategoryAlreadyExistsException;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class CreateCategoryHandlerTest extends TestCase
{
    public function testItCreatesCategoryWithoutDescription(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $repository = new CreateCategoryInMemoryRepository();
        $handler = new CreateCategoryHandler(
            $repository,
            new CategoryKeyGenerator(),
        );

        $response = $handler(new CreateCategoryCommand(
            'actor-id',
            $academyId->value(),
            new CreateCategoryInput(
                'Categoria A',
                4,
                5,
                null,
            )
        ));

        self::assertSame($academyId->value(), $response->toArray()['academyId']);
        self::assertSame('CATEGORIA-A', $response->toArray()['categoryKey']);
        self::assertSame('Categoria A', $response->toArray()['name']);
        self::assertSame(4, $response->toArray()['minAge']);
        self::assertSame(5, $response->toArray()['maxAge']);
        self::assertNull($response->toArray()['description']);
    }

    public function testItRejectsDuplicatedCategoryKeyWithinSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $existing = Category::create(
            new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'CATEGORIA-A',
            new Name('Categoria A'),
            new MinimumAge(4),
            new MaximumAge(5),
            null,
            AuditTrail::create('actor-id'),
        );

        $repository = new CreateCategoryInMemoryRepository($existing);
        $handler = new CreateCategoryHandler(
            $repository,
            new CategoryKeyGenerator(),
        );

        $this->expectException(CategoryAlreadyExistsException::class);

        $handler(new CreateCategoryCommand(
            'actor-id',
            $academyId->value(),
            new CreateCategoryInput(
                'Categoria A',
                4,
                5,
                null,
            )
        ));
    }
}

final class CreateCategoryInMemoryRepository implements CategoryRepository
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
                && $category->status()->value() === CategoryStatus::active()->value()
        ));
    }

    public function findActiveOptionsByAcademy(AcademyId $academyId): array
    {
        return [];
    }

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        return [
            'items' => array_values(array_filter(
                $this->items,
                static fn (Category $category): bool => $category->academyId()->value() === $academyId->value()
            )),
            'total' => count($this->items),
        ];
    }
}
