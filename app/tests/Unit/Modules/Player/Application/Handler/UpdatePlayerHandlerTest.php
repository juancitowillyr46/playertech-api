<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Application\Dto\UpdatePlayerInput;
use App\Modules\Player\Application\Handler\UpdatePlayerHandler;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class UpdatePlayerHandlerTest extends TestCase
{
    public function testItUpdatesThePlayerWithinTheAcademyAndSetsCategory(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d70');

        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new UpdatePlayerCategoryInMemoryRepository(
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
            $playerId,
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
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(
            new PlayerFinder($playerRepository),
            new CategoryFinder($categoryRepository),
            $playerRepository,
        );

        $response = $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            new UpdatePlayerInput(
                'DNI',
                'Juan Carlos',
                'Pérez Gómez',
                '2014-05-20',
                '87654321',
                $categoryId->value(),
            ),
        ));

        self::assertSame('Juan Carlos', $response->toArray()['firstName']);
        self::assertSame('Pérez Gómez', $response->toArray()['lastName']);
        self::assertSame('2014-05-20', $response->toArray()['birthDate']);
        self::assertSame('87654321', $response->toArray()['documentNumber']);
        self::assertSame($categoryId->value(), $response->toArray()['categoryId']);
    }

    public function testItKeepsExistingBirthDateWhenUpdateBirthDateIsMissing(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');

        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new UpdatePlayerCategoryInMemoryRepository();

        $playerRepository->save(Player::create(
            $playerId,
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
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(
            new PlayerFinder($playerRepository),
            new CategoryFinder($categoryRepository),
            $playerRepository,
        );

        $response = $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            new UpdatePlayerInput(
                'DNI',
                'Juan Carlos',
                'Pérez Gómez',
                null,
                '87654321',
            ),
        ));

        self::assertSame('2014-05-18', $response->toArray()['birthDate']);
    }

    public function testItRejectsDuplicateDocumentNumberWithinTheSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $otherPlayerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d91');

        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new UpdatePlayerCategoryInMemoryRepository();

        $playerRepository->save(Player::create(
            $playerId,
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
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));
        $playerRepository->save(Player::create(
            $otherPlayerId,
            $academyId,
            'DNI',
            'Pedro',
            'López',
            new \DateTimeImmutable('2014-06-18'),
            '87654321',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(
            new PlayerFinder($playerRepository),
            new CategoryFinder($categoryRepository),
            $playerRepository,
        );

        $this->expectException(PlayerAlreadyExistsException::class);

        $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            new UpdatePlayerInput(
                'DNI',
                'Juan Carlos',
                'Pérez Gómez',
                '2014-05-20',
                '87654321',
            ),
        ));
    }

    public function testItRejectsDuplicateEmailWithinTheSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $otherPlayerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d91');

        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new UpdatePlayerCategoryInMemoryRepository();

        $playerRepository->save(Player::create(
            $playerId,
            $academyId,
            'DNI',
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            'juan@example.com',
            '3125953354',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));
        $playerRepository->save(Player::create(
            $otherPlayerId,
            $academyId,
            'DNI',
            'Pedro',
            'López',
            new \DateTimeImmutable('2014-06-18'),
            '87654321',
            'pedro@example.com',
            '3001112233',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(
            new PlayerFinder($playerRepository),
            new CategoryFinder($categoryRepository),
            $playerRepository,
        );

        $this->expectException(PlayerAlreadyExistsException::class);
        $this->expectExceptionMessage('El correo electrónico ya existe para esta academia.');

        $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            new UpdatePlayerInput(
                'DNI',
                'Juan Carlos',
                'Pérez Gómez',
                '2014-05-20',
                '12345678',
                null,
                'pedro@example.com',
                '3009998888',
            ),
        ));
    }

    public function testItAllowsDuplicatedPhoneWithinTheSameAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $otherPlayerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d91');

        $playerRepository = new InMemoryPlayerRepository();
        $categoryRepository = new UpdatePlayerCategoryInMemoryRepository();

        $playerRepository->save(Player::create(
            $playerId,
            $academyId,
            'DNI',
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            'juan@example.com',
            '3125953354',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));
        $playerRepository->save(Player::create(
            $otherPlayerId,
            $academyId,
            'DNI',
            'Pedro',
            'López',
            new \DateTimeImmutable('2014-06-18'),
            '87654321',
            'pedro@example.com',
            '3001112233',
            null,
            null,
            null,
            null,
            null,
            null,
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new UpdatePlayerHandler(
            new PlayerFinder($playerRepository),
            new CategoryFinder($categoryRepository),
            $playerRepository,
        );

        $response = $handler(new UpdatePlayerCommand(
            '019eec93-9a11-7432-bd04-52306b2b3d8e',
            $academyId->value(),
            $playerId->value(),
            new UpdatePlayerInput(
                'DNI',
                'Juan Carlos',
                'Pérez Gómez',
                '2014-05-20',
                '12345678',
                null,
                'juan@example.com',
                '3001112233',
            ),
        ));

        self::assertSame('+573001112233', $response->toArray()['phone']);
    }
}

final class UpdatePlayerCategoryInMemoryRepository implements CategoryRepository
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
                && $category->status()->value() === CategoryStatus::active()->value()
        ));
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
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
