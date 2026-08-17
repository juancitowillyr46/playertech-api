<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Team\Application\Handler\ListTeamOptionsHandler;
use App\Modules\Team\Application\Query\ListTeamOptionsQuery;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\Team\Domain\Team\TeamStatus;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ListTeamOptionsHandlerTest extends TestCase
{
    public function testItReturnsOnlyActiveTeamsMatchingTheQuery(): void
    {
        $academyId = AcademyId::generate();
        $categoryId = CategoryId::generate();

        $categoryRepository = new InMemoryTeamOptionsCategoryRepository();
        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB15',
            new Name('Sub 15'),
            new MinimumAge(14),
            new MaximumAge(15),
            new Description('Base category'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $activeTeam = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Team Alpha'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );
        $inactiveTeam = Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Team Beta'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );
        $inactiveTeam->inactivate('actor-id');

        $teamRepository = new InMemoryTeamOptionsRepository($activeTeam, $inactiveTeam);
        $handler = new ListTeamOptionsHandler($teamRepository, new CategoryFinder($categoryRepository));

        $result = $handler(new ListTeamOptionsQuery($academyId, 'alpha'));

        self::assertCount(1, $result);
        self::assertSame('Team Alpha', $result[0]->toArray()['name']);
        self::assertSame('Sub 15', $result[0]->toArray()['categoryName']);
        self::assertSame(TeamStatus::active()->value(), $result[0]->toArray()['status']);
    }
}

final class InMemoryTeamOptionsRepository implements TeamRepository
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

final class InMemoryTeamOptionsCategoryRepository implements CategoryRepository
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
