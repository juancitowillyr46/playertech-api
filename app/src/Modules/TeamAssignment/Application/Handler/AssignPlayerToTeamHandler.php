<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\TeamAssignment\Application\Command\AssignPlayerToTeamCommand;
use App\Modules\TeamAssignment\Application\Response\TeamAssignmentResponse;
use App\Modules\TeamAssignment\Domain\Exception\TeamAssignmentAlreadyExistsException;
use App\Modules\TeamAssignment\Domain\Exception\TeamAssignmentNotFoundException;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignment;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class AssignPlayerToTeamHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private TeamRepository $teamRepository,
        private TeamAssignmentRepository $assignmentRepository
    ) {
    }

    public function __invoke(AssignPlayerToTeamCommand $command): TeamAssignmentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $playerId = new PlayerId($command->playerId);
        $teamId = new TeamId($command->teamId);
        $startDate = new \DateTimeImmutable($command->startDate);

        $player = $this->playerRepository->findById($academyId, $playerId);
        if (null === $player) {
            throw new TeamAssignmentNotFoundException();
        }

        if (null === $this->teamRepository->findById($academyId, $teamId)) {
            throw new TeamAssignmentNotFoundException();
        }

        if (null !== $this->assignmentRepository->findByPlayerAndTeam($academyId, $playerId, $teamId)) {
            throw new TeamAssignmentAlreadyExistsException();
        }

        $assignment = TeamAssignment::create(
            TeamAssignmentId::generate(),
            $academyId,
            $player->id(),
            $teamId,
            $startDate,
            AuditTrail::create($command->actorId)
        );

        $this->assignmentRepository->save($assignment);

        return TeamAssignmentResponse::fromEntity($assignment);
    }
}
