<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Domain\TeamAssignment;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class TeamAssignmentTest extends TestCase
{
    public function testItCreatesActiveNonPrimaryAssignment(): void
    {
        $assignment = TeamAssignment::create(
            TeamAssignmentId::generate(),
            AcademyId::generate(),
            PlayerId::generate(),
            TeamId::generate(),
            new \DateTimeImmutable('2026-07-08'),
            AuditTrail::create('actor-id'),
        );

        self::assertTrue($assignment->isActive());
        self::assertFalse($assignment->isPrimary());
        self::assertNull($assignment->endDate());
    }

    public function testItMarksAsPrimaryAndFinalizes(): void
    {
        $assignment = TeamAssignment::create(
            TeamAssignmentId::generate(),
            AcademyId::generate(),
            PlayerId::generate(),
            TeamId::generate(),
            new \DateTimeImmutable('2026-07-08'),
            AuditTrail::create('actor-id'),
        );

        $assignment->markPrimary('actor-2');
        self::assertTrue($assignment->isPrimary());

        $assignment->finalize(new \DateTimeImmutable('2026-07-09'), 'actor-3');

        self::assertFalse($assignment->isActive());
        self::assertFalse($assignment->isPrimary());
        self::assertSame('actor-3', $assignment->auditTrail()?->updatedBy());
    }
}
