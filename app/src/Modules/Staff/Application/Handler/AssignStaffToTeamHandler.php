<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Application\Command\AssignStaffToTeamCommand;
use App\Modules\Staff\Application\Response\TeamStaffAssignmentResponse;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository;
use App\Modules\Team\Domain\Team\TeamId;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Domain\ValueObject\AuditTrail;
final readonly class AssignStaffToTeamHandler
{
    public function __construct(private StaffRepository $staffRepository, private TeamRepository $teamRepository, private TeamStaffAssignmentRepository $assignmentRepository) {}
    public function __invoke(AssignStaffToTeamCommand $command): TeamStaffAssignmentResponse
    {
        $academyId = new AcademyId($command->academyId);
        $staffId = new StaffId($command->staffId);
        $teamId = new TeamId($command->teamId);
        $staff = $this->staffRepository->findById($academyId, $staffId) ?? throw new \App\Modules\Staff\Domain\Exception\StaffNotFoundException();
        if (null === $this->teamRepository->findById($academyId, $teamId)) { throw new \App\Modules\Team\Domain\Exception\TeamNotFoundException(); }
        if (null !== $this->assignmentRepository->findByStaffAndTeam($academyId, $staffId, $teamId)) { throw new \App\Modules\Staff\Domain\Exception\StaffAlreadyExistsException(); }
        $assignment = TeamStaffAssignment::create(TeamStaffAssignmentId::generate(), $academyId, $staff->id(), $teamId, $command->role, AuditTrail::create($command->actorId));
        $this->assignmentRepository->save($assignment);
        return TeamStaffAssignmentResponse::fromAssignment($assignment);
    }
}
