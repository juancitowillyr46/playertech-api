<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Guardian\Application\Player\ListByGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Guardian\Application\Player\ListByGuardian\ListGuardianPlayersHandler;
use App\Modules\Guardian\Application\Player\ListByGuardian\ListGuardianPlayersQuery;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use PHPUnit\Framework\TestCase;

final class ListGuardianPlayersHandlerTest extends TestCase
{
    public function testItListsPlayersRelatedToGuardian(): void
    {
        $academyId = AcademyId::generate();
        $guardianId = LegalGuardianId::generate();
        $playerId = PlayerId::generate();
        $categoryId = CategoryId::generate();

        $playerRepository = new InMemoryPlayerRepository();
        $guardianRepository = new InMemoryGuardianRepository(
            LegalGuardian::create($guardianId, $academyId, 'José', 'Castaño', '+573213755187', 'jose@example.com', 'CC', '12345678', null, 'Padre', AuditTrail::create('actor')),
        );
        $playerGuardianRepository = new InMemoryPlayerGuardianRepository(
            PlayerGuardian::create(PlayerGuardianId::generate(), $academyId, $playerId, $guardianId, true, AuditTrail::create('actor')),
        );
        $categoryRepository = new InMemoryCategoryRepository(
            Category::create($categoryId, $academyId, 'SUB-15', new Name('Sub 15'), new MinimumAge(14), new MaximumAge(15), new Description('Categoria'), AuditTrail::create('actor')),
        );

        $playerRepository->save(Player::create(
            $playerId,
            $academyId,
            'CC',
            'Juan',
            'Rodas',
            new \DateTimeImmutable('2009-09-04'),
            '1088329031',
            null,
            null,
            null,
            'Masculino',
            null,
            null,
            $categoryId,
            null,
            AuditTrail::create('actor'),
        ));

        $handler = new ListGuardianPlayersHandler($playerRepository, $guardianRepository, $playerGuardianRepository, $categoryRepository);
        $result = $handler(new ListGuardianPlayersQuery($academyId, $guardianId, new PaginationQuery(1, 20)));

        self::assertCount(1, $result);
        self::assertSame('Juan', $result[0]->toArray()['firstName']);
        self::assertSame('Rodas', $result[0]->toArray()['lastName']);
        self::assertSame('Sub 15', $result[0]->toArray()['categoryName']);
        self::assertSame('Padre', $result[0]->toArray()['relationshipName']);
        self::assertTrue($result[0]->toArray()['principal']);
    }
}

final class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var array<string, Player> */
    private array $items = [];

    public function save(Player $player): void { $this->items[$player->id()->value()] = $player; }
    public function findById(AcademyId $academyId, PlayerId $playerId): ?Player { return $this->items[$playerId->value()] ?? null; }
    public function findOneByDocumentNumber(AcademyId $academyId, string $documentNumber): ?Player { return null; }
    public function findOneByEmail(AcademyId $academyId, string $email): ?Player { return null; }
    public function findAvailableByGuardian(AcademyId $academyId, LegalGuardianId $guardianId, ?string $query = null): array { return []; }
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination, ?string $gender = null, ?string $categoryId = null, ?string $documentNumber = null, ?string $documentType = null, ?string $firstName = null, ?string $lastName = null, ?string $fullName = null, ?string $createdAtFrom = null, ?string $createdAtTo = null, ?string $birthDateFrom = null, ?string $birthDateTo = null): array { return ['items' => [], 'total' => 0]; }
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
    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian { return $this->items[$guardianId->value()] ?? null; }
    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian { return null; }
    public function findAvailableByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId, ?string $query = null): array { return []; }
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination, ?string $documentNumber = null, ?string $documentType = null, ?string $firstName = null, ?string $lastName = null, ?string $fullName = null): array { return ['items' => [], 'total' => 0]; }
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
    public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian { return $this->items[$playerGuardianId->value()] ?? null; }
    public function findByPlayerAndGuardian(AcademyId $academyId, PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian { return null; }
    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array { return []; }
    public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array
    {
        return array_values(array_filter($this->items, static fn (PlayerGuardian $relation): bool => $relation->academyId()->equals($academyId) && $relation->guardianId()->equals($guardianId)));
    }
    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?PlayerGuardian { return null; }
    public function remove(PlayerGuardian $playerGuardian): void { unset($this->items[$playerGuardian->id()->value()]); }
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
    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category { return $this->items[$categoryId->value()] ?? null; }
    public function findByCategoryKey(AcademyId $academyId, string $categoryKey): ?Category { return null; }
    public function findActiveByAcademy(AcademyId $academyId): array { return []; }
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array { return ['items' => [], 'total' => 0]; }
}
