<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Category\Domain\Category\CategoryRepository;
use App\Modules\Category\Domain\Category\CategoryStatus;
use App\Modules\Team\Application\Command\CreateTeamCommand;
use App\Modules\Team\Application\Dto\CreateTeamInput;
use App\Modules\Team\Application\Handler\CreateTeamHandler;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Application\Pagination\PaginationQuery;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class CreateTeamHandlerTest extends TestCase
{
    public function testItCreatesATeamWhenCategoryIsActiveAndNameIsUnique(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = CategoryId::generate();

        $categoryRepository = new InMemoryCreateTeamCategoryRepository();
        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB16',
            new Name('Sub 16'),
            new MinimumAge(14),
            new MaximumAge(16),
            new Description('Base category'),
            AuditTrail::create('system'),
        ));

        $teamRepository = new InMemoryCreateTeamRepository();
        $handler = new CreateTeamHandler(
            $teamRepository,
            new CategoryFinder($categoryRepository),
        );

        $response = $handler(new CreateTeamCommand(
            'actor-id',
            $academyId->value(),
            new CreateTeamInput(
                $categoryId->value(),
                'Sub-16 A',
            )
        ));

        self::assertInstanceOf(TeamResponse::class, $response);
        self::assertSame($academyId->value(), $response->toArray()['academyId']);
        self::assertSame($categoryId->value(), $response->toArray()['categoryId']);
        self::assertSame('Sub 16', $response->toArray()['categoryName']);
        self::assertSame('Sub-16 A', $response->toArray()['name']);
    }

    public function testItRejectsDuplicateTeamNameWithinTheSameCategory(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = CategoryId::generate();

        $categoryRepository = new InMemoryCreateTeamCategoryRepository();
        $categoryRepository->save(Category::create(
            $categoryId,
            $academyId,
            'SUB16',
            new Name('Sub 16'),
            new MinimumAge(14),
            new MaximumAge(16),
            new Description('Base category'),
            AuditTrail::create('system'),
        ));

        $teamRepository = new InMemoryCreateTeamRepository();
        $teamRepository->save(Team::create(
            TeamId::generate(),
            $academyId,
            $categoryId,
            new Name('Sub-16 A'),
            AuditTrail::create('system'),
        ));

        $handler = new CreateTeamHandler(
            $teamRepository,
            new CategoryFinder($categoryRepository),
        );

        $this->expectException(\App\Modules\Team\Domain\Exception\TeamAlreadyExistsException::class);

        $handler(new CreateTeamCommand(
            'actor-id',
            $academyId->value(),
            new CreateTeamInput(
                $categoryId->value(),
                'Sub-16 A',
            )
        ));
    }

    public function testItRejectsInactiveCategories(): void
    {
        $academyId = new AcademyId('019eec93-9a11-7432-bd04-52306b2b3d8f');
        $categoryId = CategoryId::generate();

        $categoryRepository = new InMemoryCreateTeamCategoryRepository();
        $category = Category::create(
            $categoryId,
            $academyId,
            'SUB16',
            new Name('Sub 16'),
            new MinimumAge(14),
            new MaximumAge(16),
            new Description('Base category'),
            AuditTrail::create('system'),
        );
        $category->inactivate('actor-id');
        $categoryRepository->save($category);

        $handler = new CreateTeamHandler(
            new InMemoryCreateTeamRepository(),
            new CategoryFinder($categoryRepository),
        );

        $this->expectException(\App\Modules\Category\Domain\Exception\CategoryInactiveException::class);

        $handler(new CreateTeamCommand(
            'actor-id',
            $academyId->value(),
            new CreateTeamInput(
                $categoryId->value(),
                'Sub-16 A',
            )
        ));
    }
}

final class InMemoryCreateTeamRepository implements TeamRepository
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
        return [
            'items' => array_values(array_filter(
                $this->teams,
                static fn (Team $team): bool => $team->academyId()->value() === $academyId->value()
            )),
            'total' => count($this->teams),
        ];
    }
}

final class InMemoryCreateTeamCategoryRepository implements CategoryRepository
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
