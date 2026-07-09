<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Application\Command\FinalizeTeamAssignmentCommand;
use App\Modules\TeamAssignment\Application\Handler\FinalizeTeamAssignmentHandler;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Shared\Domain\ValueObject\AuditTrail;
use PHPUnit\Framework\TestCase;

final class FinalizeTeamAssignmentHandlerTest extends TestCase
{
    public function testItFinalizesPrimaryAssignmentAndPromotesReplacement(): void
    {
        $academyId = AcademyId::generate();
        $playerId = PlayerId::generate();
        $repository = new InMemoryTeamAssignmentRepository();

        $primary = TeamAssignment::create(TeamAssignmentId::generate(), $academyId, $playerId, TeamId::generate(), new \DateTimeImmutable('2026-07-08'), AuditTrail::create('actor-id'));
        $replacement = TeamAssignment::create(TeamAssignmentId::generate(), $academyId, $playerId, TeamId::generate(), new \DateTimeImmutable('2026-07-09'), AuditTrail::create('actor-id'));
        $primary->markPrimary('actor-id');
        $repository->save($primary);
        $repository->save($replacement);

        $handler = new FinalizeTeamAssignmentHandler($repository);
        $handler(new FinalizeTeamAssignmentCommand('actor-id', $academyId->value(), $primary->id()->value()));

        self::assertFalse($repository->items[$primary->id()->value()]->isActive());
        self::assertTrue($repository->items[$replacement->id()->value()]->isPrimary());
    }
}
