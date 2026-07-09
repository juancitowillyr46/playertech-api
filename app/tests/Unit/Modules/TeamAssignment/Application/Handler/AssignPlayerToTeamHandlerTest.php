<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Tests\Unit\Modules\Player\Application\Handler\InMemoryPlayerRepository;
use App\Modules\Team\Domain\Team\Team;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\TeamAssignment\Application\Command\AssignPlayerToTeamCommand;
use App\Modules\TeamAssignment\Application\Handler\AssignPlayerToTeamHandler;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class AssignPlayerToTeamHandlerTest extends TestCase
{
    public function testItAssignsPlayerToTeam(): void
    {
        $academyId = AcademyId::generate();
        $playerRepository = new InMemoryPlayerRepository();
        $teamRepository = $this->createMock(\App\Modules\Team\Domain\Team\TeamRepository::class);
        $assignmentRepository = new InMemoryTeamAssignmentRepository();

        $player = Player::create(
            PlayerId::generate(),
            $academyId,
            'Juan',
            'Pérez',
            new \DateTimeImmutable('2014-05-18'),
            '12345678',
            null,
            null,
            AuditTrail::create('actor-id'),
        );
        $playerRepository->save($player);

        $team = Team::create(
            TeamId::generate(),
            $academyId,
            \App\Modules\Category\Domain\Category\CategoryId::generate(),
            new Name('Sub-12 A'),
            AuditTrail::create('actor-id'),
        );
        $teamRepository->method('findById')->willReturn($team);

        $handler = new AssignPlayerToTeamHandler($playerRepository, $teamRepository, $assignmentRepository);
        $response = $handler(new AssignPlayerToTeamCommand(
            'actor-id',
            $academyId->value(),
            $player->id()->value(),
            $team->id()->value(),
            '2026-07-08',
        ));

        self::assertSame($player->id()->value(), $response->toArray()['player_id']);
        self::assertSame($team->id()->value(), $response->toArray()['team_id']);
        self::assertCount(1, $assignmentRepository->items);
    }
}
