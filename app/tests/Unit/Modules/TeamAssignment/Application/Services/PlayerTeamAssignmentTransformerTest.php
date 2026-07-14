<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\Category;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Application\Services\PlayerTeamAssignmentTransformer;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class PlayerTeamAssignmentTransformerTest extends TestCase
{
    public function testItBuildsNestedTeamInformation(): void
    {
        $academyId = AcademyId::generate();
        $teamRepository = $this->createMock(\App\Modules\Team\Domain\Team\TeamRepository::class);
        $categoryRepository = $this->createMock(\App\Modules\Category\Domain\Category\CategoryRepository::class);
        $transformer = new PlayerTeamAssignmentTransformer($teamRepository, $categoryRepository);

        $category = Category::create(
            CategoryId::generate(),
            $academyId,
            'U12',
            new Name('Sub 12'),
            new MinimumAge(10),
            new MaximumAge(12),
            new Description('Categoría sub 12'),
            AuditTrail::create('actor-id'),
        );
        $team = Team::create(
            TeamId::generate(),
            $academyId,
            $category->id(),
            new Name('Sub 12 A'),
            AuditTrail::create('actor-id'),
        );
        $assignment = TeamAssignment::create(
            TeamAssignmentId::generate(),
            $academyId,
            \App\Modules\Player\Domain\Player\PlayerId::generate(),
            $team->id(),
            new \DateTimeImmutable('2026-07-08'),
            AuditTrail::create('actor-id'),
        );

        $teamRepository->method('findById')->willReturn($team);
        $categoryRepository->method('findById')->willReturn($category);

        $response = $transformer->transform($assignment)->toArray();

        self::assertSame($team->id()->value(), $response['team']['id']);
        self::assertSame('Sub 12 A', $response['team']['name']);
        self::assertSame($category->id()->value(), $response['team']['categoryId']);
        self::assertSame('Sub 12', $response['team']['categoryName']);
    }
}
