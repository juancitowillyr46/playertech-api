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
use PHPUnit\Framework\TestCase;

final class PlayerImportTemplateFactoryTest extends TestCase
{
    public function testItBuildsAPlayerImportTemplateWithTheSelectedCategory(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');
        $secondCategoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d71');
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
            ),
            Category::create(
                $secondCategoryId,
                $academyId,
                'SUB-16',
                new Name('Sub 16'),
                new MinimumAge(15),
                new MaximumAge(16),
                new Description('Categoria competitiva'),
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8d'),
            )
        );

        $factory = new PlayerImportTemplateFactory($repository);
        $spreadsheet = $factory->create($academyId);

        self::assertSame('Referencias', $spreadsheet->getSheet(0)->getTitle());
        self::assertSame('Datos', $spreadsheet->getSheet(1)->getTitle());
        self::assertSame('Referencias', $spreadsheet->getSheetByName('Referencias')->getTitle());
        self::assertSame('Instrucciones:', $spreadsheet->getSheetByName('Referencias')->getCell('A1')->getValue());
        self::assertSame('Categorías disponibles (categories)', $spreadsheet->getSheetByName('Referencias')->getCell('A8')->getValue());
        self::assertSame('Nombre', $spreadsheet->getSheetByName('Referencias')->getCell('A9')->getValue());
        self::assertSame('Código', $spreadsheet->getSheetByName('Referencias')->getCell('B9')->getValue());
        self::assertSame('Sub 14', $spreadsheet->getSheetByName('Referencias')->getCell('A10')->getValue());
        self::assertSame('SUB-14', $spreadsheet->getSheetByName('Referencias')->getCell('B10')->getValue());
        self::assertSame('Sub 16', $spreadsheet->getSheetByName('Referencias')->getCell('A11')->getValue());
        self::assertSame('SUB-16', $spreadsheet->getSheetByName('Referencias')->getCell('B11')->getValue());
        self::assertSame('Formas correctas', $spreadsheet->getSheetByName('Referencias')->getCell('A13')->getValue());
        self::assertSame('Campos', $spreadsheet->getSheetByName('Referencias')->getCell('A14')->getValue());
        self::assertSame('Formatos', $spreadsheet->getSheetByName('Referencias')->getCell('B14')->getValue());
        self::assertSame('Ejemplos', $spreadsheet->getSheetByName('Referencias')->getCell('C14')->getValue());
        self::assertSame(3125953354, $spreadsheet->getSheetByName('Referencias')->getCell('C17')->getValue());
        self::assertSame('Tipo de documento (documentType)', $spreadsheet->getSheetByName('Referencias')->getCell('A19')->getValue());
        self::assertSame('RC', $spreadsheet->getSheetByName('Referencias')->getCell('B26')->getValue());
        self::assertSame('Nacionalidades (nationality)', $spreadsheet->getSheetByName('Referencias')->getCell('A28')->getValue());
        self::assertSame('Pies dominantes (dominantFoot)', $spreadsheet->getSheetByName('Referencias')->getCell('A37')->getValue());
        self::assertSame('Genero (gender)', $spreadsheet->getSheetByName('Referencias')->getCell('A43')->getValue());
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
