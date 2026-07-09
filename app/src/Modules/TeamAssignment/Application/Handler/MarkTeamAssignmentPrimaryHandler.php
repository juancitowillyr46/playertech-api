<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\TeamAssignment\Application\Command\MarkTeamAssignmentPrimaryCommand;
use App\Modules\TeamAssignment\Application\Response\TeamAssignmentResponse;
use App\Modules\TeamAssignment\Domain\Exception\TeamAssignmentNotFoundException;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class MarkTeamAssignmentPrimaryHandler
{
    public function __construct(private TeamAssignmentRepository $assignmentRepository)
    {
    }

    public function __invoke(MarkTeamAssignmentPrimaryCommand $command): TeamAssignmentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $assignment = $this->assignmentRepository->findById($academyId, new \App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentId($command->assignmentId));

        if (null === $assignment) {
            throw new TeamAssignmentNotFoundException();
        }

        $currentPrimary = $this->assignmentRepository->findPrimaryByPlayer($academyId, $assignment->playerId());
        if (null !== $currentPrimary && $currentPrimary->id()->value() !== $assignment->id()->value()) {
            $currentPrimary->unmarkPrimary($command->actorId);
            $this->assignmentRepository->save($currentPrimary);
        }

        $assignment->markPrimary($command->actorId);
        $this->assignmentRepository->save($assignment);

        return TeamAssignmentResponse::fromEntity($assignment);
    }
}
