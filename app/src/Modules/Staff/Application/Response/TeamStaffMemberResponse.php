<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Response;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
final readonly class TeamStaffMemberResponse
{
    public function __construct(
        public string $assignmentId,
        public string $staffId,
        public string $userId,
        public string $teamId,
        public string $role,
    ) {}
    public static function fromEntities(TeamStaffAssignment $assignment, Staff $staff): self
    {
        return new self(
            $assignment->id()->value(),
            $staff->id()->value(),
            $staff->userId(),
            $assignment->teamId()->value(),
            $assignment->role()->value(),
        );
    }
    public function toArray(): array
    {
        return [
            'assignmentId' => $this->assignmentId,
            'staffId' => $this->staffId,
            'userId' => $this->userId,
            'teamId' => $this->teamId,
            'role' => $this->role,
        ];
    }
}
