<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Team\Application\Command\UpdateTeamCommand;
use App\Modules\Team\Application\Dto\UpdateTeamInput;
use App\Modules\Team\Application\Handler\UpdateTeamHandler;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Application\Services\TeamFinder;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class UpdateTeamHandlerTest extends TestCase
{
    public function testItUpdatesCategoryAndName(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $team = Team::create(
            TeamId::generate(),
            $academyId,
            CategoryId::generate(),
            new Name('Sub-16 A'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        );

        $teamRepository = new InMemoryUpdateTeamRepository($team);
        $categoryRepository = new UpdateTeamInMemoryCategoryRepository();
        $categoryFinder = new CategoryFinder($categoryRepository);
        $handler = new UpdateTeamHandler($teamRepository, new TeamFinder($teamRepository), $categoryFinder);

        $newCategoryId = CategoryId::generate();
        $categoryRepository->save(Category::create(
            $newCategoryId,
            $academyId,
            'SUB18',
            new Name('Sub 18'),
            new MinimumAge(16),
            new MaximumAge(18),
            new Description('Categoria superior'),
            AuditTrail::create('019eec93-9a11-7432-bd04-52306b2b3d8e'),
        ));

        $response = $handler(new UpdateTeamCommand(
            'actor-id',
            $academyId->value(),
            $team->id()->value(),
            new UpdateTeamInput(
                $newCategoryId->value(),
                'Sub-18 A',
            )
        ));

        self::assertInstanceOf(TeamResponse::class, $response);
        self::assertSame($newCategoryId->value(), $response->toArray()['categoryId']);
        self::assertSame('Sub 18', $response->toArray()['categoryName']);
        self::assertSame('Sub-18 A', $response->toArray()['name']);
    }
}

final class InMemoryUpdateTeamRepository implements TeamRepository
{
    public function __construct(
        private Team $team,
    ) {
    }

    public function save(Team $team): void
    {
        $this->team = $team;
    }

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team
    {
        if (
            $this->team->academyId()->value() === $academyId->value()
            && $this->team->id()->value() === $teamId->value()
        ) {
            return $this->team;
        }

        return null;
    }

    public function findOneByAcademyCategoryAndName(AcademyId $academyId, CategoryId $categoryId, Name $name): ?Team
    {
        return null;
    }

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return [
            'items' => [$this->team],
            'total' => 1,
        ];
    }
}

final class UpdateTeamInMemoryCategoryRepository implements CategoryRepository
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
        foreach ($this->categories as $category) {
            if (
                $category->academyId()->value() === $academyId->value()
                && $category->categoryKey() === strtoupper(trim($categoryKey))
            ) {
                return $category;
            }
        }

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

    public function findAllByAcademy(AcademyId $academyId, \App\Shared\Application\Pagination\PaginationQuery $pagination): array
    {
        return [
            'items' => $this->categories,
            'total' => count($this->categories),
        ];
    }
}
