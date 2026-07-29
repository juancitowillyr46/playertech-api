<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Service;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Player\Application\Service\PlayerImportTemplateFactory;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPUnit\Framework\TestCase;

final class PlayerImportTemplateFactoryTest extends TestCase
{
    public function testItBuildsAPlayerImportTemplateWithTheSelectedCategory(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');
        $repository = new InMemoryCategoryRepository(
            Category::create(
                $categoryId,
                $academyId,
                'SUB-14',
                new Name('Sub 14'),
                new MinimumAge(13),
                new MaximumAge(14),
                new Description('Categoria formativa'),
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
            )
        );

        $factory = new PlayerImportTemplateFactory($repository);
        $spreadsheet = $factory->create($academyId, $categoryId);

        self::assertSame('Datos', $spreadsheet->getSheet(0)->getTitle());
        self::assertSame('Referencias', $spreadsheet->getSheet(1)->getTitle());
        self::assertSame('Categoría seleccionada', $spreadsheet->getSheet(1)->getCell('A1')->getValue());
        self::assertSame('Sub 14', $spreadsheet->getSheet(1)->getCell('B2')->getValue());
        self::assertSame('SUB-14', $spreadsheet->getSheet(1)->getCell('D2')->getValue());
    }
}

final class InMemoryCategoryRepository implements CategoryRepository
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

        if (null === $category || !$category->academyId()->equals($academyId)) {
            return null;
        }

        return $category;
    }

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
    {
        foreach ($this->items as $category) {
            if ($category->academyId()->equals($academyId) && $category->categoryKey() === strtoupper(trim($categoryKey))) {
                return $category;
            }
        }

        return null;
    }

    public function findActiveByAcademy(AcademyId $academyId): array
    {
        return array_values(array_filter(
            $this->items,
            static fn (Category $category): bool => $category->academyId()->equals($academyId)
                && $category->status()->value() === 'ACTIVE'
        ));
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return [
            'items' => array_values(array_filter(
                $this->items,
                static fn (Category $category): bool => $category->academyId()->equals($academyId)
            )),
            'total' => count($this->items),
        ];
    }
}
