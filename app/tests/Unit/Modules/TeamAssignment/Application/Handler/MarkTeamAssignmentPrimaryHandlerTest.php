<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Application\Command\MarkTeamAssignmentPrimaryCommand;
use App\Modules\TeamAssignment\Application\Handler\MarkTeamAssignmentPrimaryHandler;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class MarkTeamAssignmentPrimaryHandlerTest extends TestCase
{
    public function testItSwitchesPrimaryAssignment(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $repository = new InMemoryTeamAssignmentRepository();

        $first = TeamAssignment::create(TeamAssignmentId::generate(), $academyId, $playerId, TeamId::generate(), new \DateTimeImmutable('2026-07-08'), AuditTrail::create('actor-id'));
        $second = TeamAssignment::create(TeamAssignmentId::generate(), $academyId, $playerId, TeamId::generate(), new \DateTimeImmutable('2026-07-08'), AuditTrail::create('actor-id'));
        $first->markPrimary('actor-id');
        $repository->save($first);
        $repository->save($second);

        $handler = new MarkTeamAssignmentPrimaryHandler($repository);
        $handler(new MarkTeamAssignmentPrimaryCommand('actor-id', $academyId->value(), $second->id()->value()));

        self::assertFalse($repository->items[$first->id()->value()]->isPrimary());
        self::assertTrue($repository->items[$second->id()->value()]->isPrimary());
    }
}
