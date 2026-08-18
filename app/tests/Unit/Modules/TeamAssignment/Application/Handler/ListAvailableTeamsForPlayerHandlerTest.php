<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Team\Application\Response\TeamOptionResponse;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\Team\Domain\Team\TeamStatus;
use App\Modules\TeamAssignment\Application\Handler\ListAvailableTeamsForPlayerHandler;
use App\Modules\TeamAssignment\Application\Query\ListAvailableTeamsForPlayerQuery;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ListAvailableTeamsForPlayerHandlerTest extends TestCase
{
    public function testItExcludesActiveTeamsAlreadyAssignedToThePlayer(): void
    {
        $academyId = AcademyId::generate();
        $playerId = \App\Modules\Player\Domain\Player\PlayerId::generate();
        $categoryId = CategoryId::generate();

        $categoryRepository = new InMemoryAvailableTeamsCategoryRepository();
        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB15',
            new Name('Sub 15'),
            new MinimumAge(14),
            new MaximumAge(15),
            new Description('Base category'),
            AuditTrail::create('actor-id'),
        ));

        $availableTeam = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Team Alpha'),
            AuditTrail::create('actor-id'),
        );
        $assignedTeam = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Team Beta'),
            AuditTrail::create('actor-id'),
        );
        $inactiveTeam = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Team Gamma'),
            AuditTrail::create('actor-id'),
        );
        $inactiveTeam->inactivate('actor-id');

        $teamRepository = new InMemoryAvailableTeamsRepository($availableTeam, $assignedTeam, $inactiveTeam);
        $assignmentRepository = new InMemoryTeamAssignmentRepository();
        $assignmentRepository->save(TeamAssignment::create(
            TeamAssignmentId::generate(),
            $academyId,
            $playerId,
            $assignedTeam->id(),
            new \DateTimeImmutable('2026-08-17'),
            AuditTrail::create('actor-id'),
        ));

        $handler = new ListAvailableTeamsForPlayerHandler(
            $teamRepository,
            $assignmentRepository,
            new CategoryFinder($categoryRepository),
        );

        $result = $handler(new ListAvailableTeamsForPlayerQuery(
            $academyId->value(),
            $playerId->value(),
            'team',
        ));

        self::assertCount(1, $result);
        self::assertSame('Team Alpha', $result[0]->toArray()['name']);
        self::assertSame('Sub 15', $result[0]->toArray()['categoryName']);
        self::assertSame(TeamStatus::active()->value(), $result[0]->toArray()['status']);
    }
}

final class InMemoryAvailableTeamsRepository implements TeamRepository
{
    /** @var Team[] */
    private array $items = [];

    public function __construct(Team ...$teams)
    {
        foreach ($teams as $team) {
            $this->items[] = $team;
        }
    }

    public function save(Team $team): void
    {
        $this->items[] = $team;
    }

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team
    {
        foreach ($this->items as $team) {
            if ($team->academyId()->value() === $academyId->value() && $team->id()->value() === $teamId->value()) {
                return $team;
            }
        }

        return null;
    }

    public function findOneByAcademyCategoryAndName(AcademyId $academyId, CategoryId $categoryId, Name $name): ?Team
    {
        return null;
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return ['items' => [], 'total' => 0];
    }

    public function findActiveByAcademyWithSearch(AcademyId $academyId, ?string $search = null): array
    {
        $search = null !== $search ? mb_strtolower(trim($search)) : '';

        return array_values(array_filter($this->items, static function (Team $team) use ($academyId, $search): bool {
            if ($team->academyId()->value() !== $academyId->value()) {
                return false;
            }

            if ($team->status()->value() !== TeamStatus::active()->value()) {
                return false;
            }

            if ('' === $search) {
                return true;
            }

            return str_contains(mb_strtolower($team->name()->value()), $search);
        }));
    }
}

final class InMemoryAvailableTeamsCategoryRepository implements CategoryRepository
{
    /** @var Category[] */
    private array $items = [];

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
        return null;
    }

    public function findActiveByAcademy(AcademyId $academyId): array
    {
        return array_values(array_filter($this->items, static fn (Category $category): bool => $category->academyId()->value() === $academyId->value() && $category->status()->value() === CategoryStatus::active()->value()));
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return ['items' => $this->items, 'total' => count($this->items)];
    }
}
