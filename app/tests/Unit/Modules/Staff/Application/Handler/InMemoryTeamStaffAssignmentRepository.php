<?php
declare(strict_types=1);
namespace App\Tests\Unit\Modules\Staff\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentRepository;
use App\Modules\Team\Domain\Team\TeamId;
final class InMemoryTeamStaffAssignmentRepository implements TeamStaffAssignmentRepository
{
    /** @var array<string, TeamStaffAssignment> */
    public array $items = [];
    public function save(TeamStaffAssignment $assignment): void { $this->items[$assignment->id()->value()] = $assignment; }
    public function findByStaffAndTeam(AcademyId $academyId, StaffId $staffId, TeamId $teamId): ?TeamStaffAssignment { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->staffId()->value()===$staffId->value() && $item->teamId()->value()===$teamId->value() && null === $item->deletedAt()) return $item; } return null; }
    public function findById(AcademyId $academyId, TeamStaffAssignmentId $assignmentId): ?TeamStaffAssignment { foreach ($this->items as $item) { if ($item->academyId()->value()===$academyId->value() && $item->id()->value()===$assignmentId->value() && null === $item->deletedAt()) return $item; } return null; }
    public function findAllByTeam(AcademyId $academyId, TeamId $teamId): array { return array_values(array_filter($this->items, fn ($item) => $item->academyId()->value()===$academyId->value() && $item->teamId()->value()===$teamId->value() && null === $item->deletedAt())); }
}
