<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Handler;
use App\Modules\Staff\Application\Query\ShowTeamStaffQuery;
use App\Modules\Staff\Application\Response\TeamStaffMemberResponse;
use App\Modules\Staff\Domain\Staff\StaffRepository;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository;
final readonly class ShowTeamStaffHandler
{
    public function __construct(private StaffRepository $staffRepository, private TeamStaffAssignmentRepository $assignmentRepository) {}
    public function __invoke(ShowTeamStaffQuery $query): array
    {
        $assignments = $this->assignmentRepository->findAllByTeam($query->academyId, $query->teamId);
        return array_values(array_filter(array_map(function ($assignment) use ($query) {
            $staff = $this->staffRepository->findById($query->academyId, $assignment->staffId());
            return null === $staff ? null : TeamStaffMemberResponse::fromEntities($assignment, $staff);
        }, $assignments)));
    }
}
