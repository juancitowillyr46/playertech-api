<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Application\Handler\ShowPlayerHandler;
use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class ShowPlayerHandlerTest extends TestCase
{
    public function testItShowsThePlayerDetailWithinTheAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $playerId = new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90');
        $guardianId = new LegalGuardianId('019eec93-9a11-7432-bd04-52306b2b3d91');
        $teamId = new TeamId('019eec93-9a11-7432-bd04-52306b2b3d92');
        $categoryId = new CategoryId('019eec93-9a11-7432-bd04-52306b2b3d93');
        $repository = new InMemoryPlayerRepository();
        $repository->save(Player::create(
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

        $guardianRepository = new InMemoryGuardianRepository(
            LegalGuardian::create(
                $guardianId,
                $academyId,
                'Juan',
                'Pérez',
                '+573125953354',
                'juan@example.com',
                'CC',
                '12345678',
                null,
                'MOTHER',
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
            )
        );

        $playerGuardianRepository = new InMemoryPlayerGuardianRepository(
            PlayerGuardian::create(
                PlayerGuardianId::generate(),
                $academyId,
                $playerId,
                $guardianId,
                true,
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
            )
        );

        $teamRepository = new InMemoryTeamRepository(
            Team::create(
                $teamId,
                $academyId,
                $categoryId,
                new Name('Team A'),
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
            )
        );

        $teamAssignmentRepository = new InMemoryTeamAssignmentRepository(
            TeamAssignment::create(
                TeamAssignmentId::generate(),
                $academyId,
                $playerId,
                $teamId,
                new \DateTimeImmutable('2026-01-01'),
                AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
            )
        );

        $handler = new ShowPlayerHandler(
            new PlayerFinder($repository),
            new class implements \App\Modules\Category\Domain\Category\CategoryRepository {
                public function save(\App\Modules\Category\Domain\Category\Category $category): void {}
                public function findById(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, \App\Modules\Category\Domain\Category\CategoryId $categoryId): ?\App\Modules\Category\Domain\Category\Category { return null; }
                public function findByCategoryKey(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, string $categoryKey): ?\App\Modules\Category\Domain\Category\Category { return null; }
                public function findActiveByAcademy(\App\Modules\Academy\Domain\Academy\AcademyId $academyId): array { return []; }
                public function findAllByAcademy(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array { return ['items' => [], 'total' => 0]; }
            },
            $playerGuardianRepository,
            $guardianRepository,
            $teamAssignmentRepository,
            $teamRepository,
        );

        $response = $handler(new ShowPlayerQuery($academyId, $playerId));

        self::assertSame('Juan', $response->toArray()['firstName']);
        self::assertSame('Pérez', $response->toArray()['lastName']);
        self::assertSame('12345678', $response->toArray()['documentNumber']);
        self::assertSame(null, $response->toArray()['phone']);
        self::assertSame(null, $response->toArray()['phoneSingle']);
        self::assertSame(['firstName' => 'Juan', 'lastName' => 'Pérez'], $response->toArray()['legalGuardianMain']);
        self::assertSame(['name' => 'Team A'], $response->toArray()['teamMain']);
    }

    public function testItRejectsMissingPlayer(): void
    {
        $this->expectException(PlayerNotFoundException::class);

        $handler = new ShowPlayerHandler(
            new PlayerFinder(new InMemoryPlayerRepository()),
            new class implements \App\Modules\Category\Domain\Category\CategoryRepository {
                public function save(\App\Modules\Category\Domain\Category\Category $category): void {}
                public function findById(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, \App\Modules\Category\Domain\Category\CategoryId $categoryId): ?\App\Modules\Category\Domain\Category\Category { return null; }
                public function findByCategoryKey(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, string $categoryKey): ?\App\Modules\Category\Domain\Category\Category { return null; }
                public function findActiveByAcademy(\App\Modules\Academy\Domain\Academy\AcademyId $academyId): array { return []; }
                public function findAllByAcademy(\App\Modules\Academy\Domain\Academy\AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array { return ['items' => [], 'total' => 0]; }
            },
            new InMemoryPlayerGuardianRepository(),
            new InMemoryGuardianRepository(),
            new InMemoryTeamAssignmentRepository(),
            new InMemoryTeamRepository(),
        );

        $handler(new ShowPlayerQuery(
            new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f'),
            new PlayerId('019eec93-9a11-7432-bd04-52306b2b3d90'),
        ));
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
    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian { return $this->items[$guardianId->value()] ?? null; }
    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian { return null; }
    public function findAvailableByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId, ?string $query = null): array { return []; }
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
    public function findById(AcademyId $academyId, PlayerGuardianId $playerGuardianId): ?PlayerGuardian { return $this->items[$playerGuardianId->value()] ?? null; }
    public function findByPlayerAndGuardian(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId, LegalGuardianId $guardianId): ?PlayerGuardian { return null; }
    public function findAllByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId): array { return []; }
    public function findAllByGuardian(AcademyId $academyId, LegalGuardianId $guardianId): array { return []; }
    public function findPrimaryByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId): ?PlayerGuardian { return $this->items[array_key_first($this->items)] ?? null; }
    public function remove(PlayerGuardian $playerGuardian): void { unset($this->items[$playerGuardian->id()->value()]); }
}

final class InMemoryTeamRepository implements TeamRepository
{
    /** @var array<string, Team> */
    private array $items = [];

    public function __construct(Team ...$teams)
    {
        foreach ($teams as $team) {
            $this->items[$team->id()->value()] = $team;
        }
    }

    public function save(Team $team): void { $this->items[$team->id()->value()] = $team; }
    public function findById(AcademyId $academyId, TeamId $teamId): ?Team { return $this->items[$teamId->value()] ?? null; }
    public function findOneByAcademyCategoryAndName(AcademyId $academyId, CategoryId $categoryId, Name $name): ?Team { return null; }
    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array { return ['items' => [], 'total' => 0]; }
    public function findActiveByAcademyWithSearch(AcademyId $academyId, ?string $search = null): array { return array_values($this->items); }
}

final class InMemoryTeamAssignmentRepository implements TeamAssignmentRepository
{
    /** @var array<string, TeamAssignment> */
    private array $items = [];

    public function __construct(TeamAssignment ...$assignments)
    {
        foreach ($assignments as $assignment) {
            $this->items[$assignment->id()->value()] = $assignment;
        }
    }

    public function save(TeamAssignment $assignment): void { $this->items[$assignment->id()->value()] = $assignment; }
    public function findById(AcademyId $academyId, TeamAssignmentId $assignmentId): ?TeamAssignment { return null; }
    public function findActiveByPlayerAndTeam(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId, TeamId $teamId): ?TeamAssignment { return null; }
    public function findAllByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId): array { return []; }
    public function findPrimaryByPlayer(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId): ?TeamAssignment { return $this->items[array_key_first($this->items)] ?? null; }
    public function findActiveByPlayerExcept(AcademyId $academyId, \App\Modules\Player\Domain\Player\PlayerId $playerId, ?TeamAssignmentId $excludedAssignmentId = null): ?TeamAssignment { return null; }
}
