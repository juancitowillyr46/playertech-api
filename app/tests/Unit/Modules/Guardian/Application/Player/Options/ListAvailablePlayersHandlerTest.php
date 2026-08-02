<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Guardian\Application\Player\Options;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Guardian\Application\Player\Options\ListAvailablePlayersHandler;
use App\Modules\Guardian\Application\Player\Options\ListAvailablePlayersQuery;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use PHPUnit\Framework\TestCase;

final class ListAvailablePlayersHandlerTest extends TestCase
{
    public function testItListsOnlyPlayersNotAlreadyAssociatedToGuardian(): void
    {
        $academyId = AcademyId::generate();
        $guardianId = LegalGuardianId::generate();
        $categoryId = CategoryId::generate();
        $availablePlayerId = PlayerId::generate();
        $linkedPlayerId = PlayerId::generate();

        $playerRepository = new InMemoryPlayerRepository(
            Player::create(
                $availablePlayerId,
                $academyId,
                'CC',
                'Juan',
                'Rodas',
                new \DateTimeImmutable('2014-05-18'),
                '12345678',
                null,
                null,
                null,
                'Masculino',
                null,
                null,
                $categoryId,
                null,
                AuditTrail::create('actor'),
            ),
            Player::create(
                $linkedPlayerId,
                $academyId,
                'CC',
                'Carlos',
                'Pérez',
                new \DateTimeImmutable('2013-02-11'),
                '87654321',
                null,
                null,
                null,
                'Masculino',
                null,
                null,
                $categoryId,
                null,
                AuditTrail::create('actor'),
            ),
        );

        $guardianRepository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                $guardianId,
                $academyId,
                'Maria',
                'Lopez',
                '+573000000000',
                'maria@example.com',
                'CC',
                '12345678',
                null,
                'Madre',
                AuditTrail::create('actor'),
            )
        );

        $playerGuardianRepository = new InMemoryPlayerGuardianRepository(
            PlayerGuardian::create(
                PlayerGuardianId::generate(),
                $academyId,
                $linkedPlayerId,
                $guardianId,
                true,
                AuditTrail::create('actor'),
            )
        );

        $categoryRepository = new InMemoryCategoryRepository(
            Category::create(
                $categoryId,
                $academyId,
                'SUB-14',
                new Name('Sub 14'),
                new MinimumAge(13),
                new MaximumAge(14),
                new Description('Categoria formativa'),
                AuditTrail::create('actor'),
            )
        );

        $handler = new ListAvailablePlayersHandler($playerRepository, $guardianRepository, $categoryRepository);
        $response = $handler(new ListAvailablePlayersQuery($academyId, $guardianId, 'Juan'));

        self::assertCount(1, $response);
        self::assertSame('Juan', $response[0]->toArray()['firstName']);
        self::assertSame('Rodas', $response[0]->toArray()['lastName']);
        self::assertSame('Sub 14', $response[0]->toArray()['categoryName']);
    }
}

final class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var Player[] */
    private array $items = [];

    public function __construct(Player ...$players)
    {
        foreach ($players as $player) {
            $this->items[$player->id()->value()] = $player;
        }
    }

    public function save(Player $player): void { $this->items[$player->id()->value()] = $player; }
    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player { return $this->items[$playerId->value()] ?? null; }
    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player { return null; }
    public function findOneByEmail(AcademyId $academyId, string $email): ?Player { return null; }
    public function findOneByPhone(AcademyId $academyId, string $phone): ?Player { return null; }
    public function findAvailableByGuardian(AcademyId $academyId, LegalGuardianId $guardianId, ?string $query = null): array
    {
        return array_values(array_filter(
            $this->items,
            function (Player $player) use ($academyId, $query): bool {
                if (!$player->academyId()->equals($academyId)) {
                    return false;
                }

                if (null !== $query && '' !== trim($query)) {
                    $needle = $this->normalizeSearchText($query);
                    $firstName = $this->normalizeSearchText($player->firstName());
                    $lastName = $this->normalizeSearchText($player->lastName());
                    $fullName = $firstName.' '.$lastName;

                    if (!str_contains($firstName, $needle) && !str_contains($lastName, $needle) && !str_contains($fullName, $needle)) {
                        return false;
                    }
                }

                return true;
            }
        ));
    }
    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination, ?string $gender = null, ?string $categoryId = null, ?string $documentNumber = null, ?string $documentType = null, ?string $firstName = null, ?string $lastName = null, ?string $fullName = null, ?string $createdAtFrom = null, ?string $createdAtTo = null, ?string $birthDateFrom = null, ?string $birthDateTo = null): array { return ['items' => [], 'total' => 0]; }

    private function normalizeSearchText(string $value): string
    {
        $trimmed = trim($value);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed);

        return mb_strtolower($normalized !== false ? $normalized : $trimmed);
    }
}

final class InMemoryGuardianRepository implements LegalGuardianRepository
{
    /** @var array<string, LegalGuardian> */
    private array $items = [];

    public function __construct(LegalGuardian ...$guardians)
    {
        foreach ($guardians as $guardian) {
            $this->items[$guardian->id()->value()] = $guardian;
        }
    }

    public function save(LegalGuardian $guardian): void { $this->items[$guardian->id()->value()] = $guardian; }
    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian
    {
        $guardian = $this->items[$guardianId->value()] ?? null;

        return null !== $guardian && $guardian->academyId()->equals($academyId) ? $guardian : null;
    }
    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian { return null; }
    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination, ?string $documentNumber = null, ?string $documentType = null, ?string $firstName = null, ?string $lastName = null, ?string $fullName = null): array { return ['items' => [], 'total' => 0]; }
}

final class InMemoryPlayerGuardianRepository implements PlayerGuardianRepository
{
    /** @var array<string, PlayerGuardian> */
    private array $items = [];

    public function __construct(PlayerGuardian ...$relations)
    {
        foreach ($relations as $relation) {
            $this->items[$relation->id()->value()] = $relation;
        }
    }

    public function save(PlayerGuardian $playerGuardian): void { $this->items[$playerGuardian->id()->value()] = $playerGuardian; }
    public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian { return null; }
    public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian { return null; }
    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array { return []; }
    public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array
    {
        return array_values(array_filter($this->items, static fn (PlayerGuardian $relation): bool => $relation->academyId()->equals($academyId) && $relation->guardianId()->equals($guardianId)));
    }
    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian { return null; }
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

    public function save(Category $category): void { $this->items[$category->id()->value()] = $category; }
    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        $category = $this->items[$categoryId->value()] ?? null;

        return null !== $category && $category->academyId()->equals($academyId) ? $category : null;
    }
    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category { return null; }
    public function findActiveByAcademy(AcademyId $academyId): array { return []; }
    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array { return ['items' => [], 'total' => 0]; }
}
