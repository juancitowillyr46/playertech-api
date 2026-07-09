<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\TeamAssignment\Application\Command\FinalizeTeamAssignmentCommand;
use App\Modules\TeamAssignment\Application\Response\TeamAssignmentResponse;
use App\Modules\TeamAssignment\Domain\Exception\TeamAssignmentNotFoundException;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class FinalizeTeamAssignmentHandler
{
    public function __construct(private TeamAssignmentRepository $assignmentRepository)
    {
    }

    public function __invoke(FinalizeTeamAssignmentCommand $command): TeamAssignmentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $assignment = $this->assignmentRepository->findById($academyId, new \App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId($command->assignmentId));

        if (null === $assignment) {
            throw new TeamAssignmentNotFoundException();
        }

        $wasPrimary = $assignment->isPrimary();
        $assignment->finalize(new \DateTimeImmutable(), $command->actorId);

        if ($wasPrimary) {
            $replacement = $this->assignmentRepository->findActiveByPlayerExcept($academyId, $assignment->playerId(), $assignment->id());
            if (null !== $replacement) {
                $replacement->markPrimary($command->actorId);
                $this->assignmentRepository->save($replacement);
            }
        }

        $this->assignmentRepository->save($assignment);

        return TeamAssignmentResponse::fromEntity($assignment);
    }
}
