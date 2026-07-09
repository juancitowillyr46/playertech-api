<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Application\Command\RemoveStaffFromTeamCommand;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository;
final readonly class RemoveStaffFromTeamHandler
{
    public function __construct(private TeamStaffAssignmentRepository $assignmentRepository) {}
    public function __invoke(RemoveStaffFromTeamCommand $command): void
    {
        $assignment = $this->assignmentRepository->findById(new AcademyId($command->academyId), new \App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId($command->assignmentId));
        if (null === $assignment) { throw new \App\Modules\Staff\Domain\Exception\StaffNotFoundException(); }
        $assignment->remove($command->actorId);
        $this->assignmentRepository->save($assignment);
    }
}
