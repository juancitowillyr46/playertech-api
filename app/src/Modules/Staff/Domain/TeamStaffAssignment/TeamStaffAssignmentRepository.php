<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\TeamStaffAssignment;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Team\Domain\Team\TeamId;
interface TeamStaffAssignmentRepository
{
    public function save(TeamStaffAssignment $assignment): void;

    public function findByStaffAndTeam(AcademyId $academyId, StaffId $staffId, TeamId $teamId): ?TeamStaffAssignment;

    public function findById(AcademyId $academyId, TeamStaffAssignmentId $assignmentId): ?TeamStaffAssignment;

    public function findAllByTeam(AcademyId $academyId, TeamId $teamId): array;
}
