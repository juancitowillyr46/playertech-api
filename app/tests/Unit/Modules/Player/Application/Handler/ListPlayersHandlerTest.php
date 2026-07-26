<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Player\Application\Handler\ListPlayersHandler;
use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Pagination\PaginationQuery;
use PHPUnit\Framework\TestCase;

final class ListPlayersHandlerTest extends TestCase
{
    public function testItListsPlayersForTheGivenAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');
        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new InMemoryCategoryRepository(
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
        $playerRepository->save(Player::create(
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
            $academyId,
            'DNI',
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            null,
            null,
            null,
            null,
            $categoryId,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ListPlayersHandler($playerRepository, $categoryRepository);

        $players = $handler(new ListPlayersQuery($academyId, new PaginationQuery()));

        self::assertCount(1, $players->items);
        self::assertSame('Juan', $players->items[0]->toArray()['firstName']);
        self::assertSame('12345678', $players->items[0]->toArray()['documentNumber']);
        self::assertSame('Sub 14', $players->items[0]->toArray()['categoryName']);
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

        if (null === $category || $category->academyId()->value() !== $academyId->value()) {
            return null;
        }

        return $category;
    }

    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category
    {
        foreach ($this->items as $category) {
            if ($category->academyId()->value() === $academyId->value() && $category->categoryKey() === strtoupper(trim($categoryKey))) {
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
        ));
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
