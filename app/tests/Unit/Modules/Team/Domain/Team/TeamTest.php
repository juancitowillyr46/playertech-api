<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Team\Domain\Team;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class TeamTest extends TestCase
{
    public function testItCreatesAnActiveTeam(): void
    {
        $team = Team::create(
            TeamId::generate(),
            AcademyId::generate(),
            CategoryId::generate(),
            new Name('Sub-16'),
            AuditTrail::create('actor-id'),
        );

        self::assertTrue($team->status()->isActive());
        self::assertSame('Sub-16', $team->name()->value());
        self::assertNotNull($team->auditTrail());
    }

    public function testItUpdatesCategoryAndName(): void
    {
        $team = Team::create(
            TeamId::generate(),
            AcademyId::generate(),
            CategoryId::generate(),
            new Name('Sub-16'),
            AuditTrail::create('actor-id'),
        );

        $newCategoryId = CategoryId::generate();
        $team->update($newCategoryId, new Name('Sub-18'), 'actor-2');

        self::assertSame($newCategoryId->value(), $team->categoryId()->value());
        self::assertSame('Sub-18', $team->name()->value());
        self::assertSame('actor-2', $team->auditTrail()?->updatedBy());
    }

    public function testItCanDeactivateAndReactivate(): void
    {
        $team = Team::create(
            TeamId::generate(),
            AcademyId::generate(),
            CategoryId::generate(),
            new Name('Sub-16'),
            AuditTrail::create('actor-id'),
        );

        $team->inactivate('actor-2');
        self::assertTrue($team->status()->isInactive());

        $team->activate('actor-3');
        self::assertTrue($team->status()->isActive());
        self::assertSame('actor-3', $team->auditTrail()?->updatedBy());
    }

    public function testItSoftDeletesAndRestores(): void
    {
        $team = Team::create(
            TeamId::generate(),
            AcademyId::generate(),
            CategoryId::generate(),
            new Name('Sub-16'),
            AuditTrail::create('actor-id'),
        );

        $team->delete('deleter');

        self::assertNotNull($team->deletedAt());
        self::assertSame('deleter', $team->deletedBy());

        $team->restore('restorer');

        self::assertNull($team->deletedAt());
        self::assertNull($team->deletedBy());
        self::assertSame('restorer', $team->auditTrail()?->updatedBy());
    }
}
