<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Application\Handler\ListTeamsHandler;
use App\Modules\Team\Application\Query\ListTeamsQuery;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\Team\Domain\Team\TeamStatus;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ListTeamsHandlerTest extends TestCase
{
    public function testItListsTeamsForTheGivenAcademy(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $repository = new InMemoryTeamRepository();
        $categoryRepository = new InMemoryCategoryRepository();
        $categoryId = CategoryId::generate();

        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB16',
            new Name('Sub 16'),
            new MinimumAge(14),
            new MaximumAge(16),
            new Description('Base category'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $repository->save(Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Sub-16 A'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $handler = new ListTeamsHandler($repository, new CategoryFinder($categoryRepository));
        $teams = $handler(new ListTeamsQuery($academyId, new PaginationQuery()));

        self::assertCount(1, $teams->items);
        self::assertSame('Sub 16', $teams->items[0]->toArray()['categoryName']);
        self::assertSame('Sub-16 A', $teams->items[0]->toArray()['name']);
        self::assertSame(TeamStatus::active()->value(), $teams->items[0]->toArray()['status']);
    }
}

final class InMemoryCategoryRepository implements CategoryRepository
{
    /** @var Category[] */
    private array $categories = [];

    public function save(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function findById(AcademyId $academyId, CategoryId $categoryId): ?Category
    {
        foreach ($this->categories as $category) {
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
        return array_values(array_filter(
            $this->categories,
            static fn (Category $category): bool => $category->academyId()->value() === $academyId->value()
                && $category->status()->value() === \App\Modules\Category\Domain\Category\CategoryStatus::active()->value()
        ));
    }

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        return [
            'items' => $this->categories,
            'total' => count($this->categories),
        ];
    }
}

final class InMemoryTeamRepository implements TeamRepository
{
    /** @var Team[] */
    private array $teams = [];

    public function save(Team $team): void
    {
        $this->teams[] = $team;
    }

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team
    {
        foreach ($this->teams as $team) {
            if ($team->academyId()->value() === $academyId->value() && $team->id()->value() === $teamId->value()) {
                return $team;
            }
        }

        return null;
    }

    public function findOneByAcademyCategoryAndName(AcademyId $academyId, CategoryId $categoryId, Name $name): ?Team
    {
        foreach ($this->teams as $team) {
            if (
                $team->academyId()->value() === $academyId->value()
                && $team->categoryId()->value() === $categoryId->value()
                && $team->name()->value() === $name->value()
            ) {
                return $team;
            }
        }

        return null;
    }

    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array
    {
        $items = array_values(array_filter(
            $this->teams,
            static fn (Team $team): bool => $team->academyId()->value() === $academyId->value()
        ));

        return [
            'items' => $items,
            'total' => count($items),
        ];
    }
}
