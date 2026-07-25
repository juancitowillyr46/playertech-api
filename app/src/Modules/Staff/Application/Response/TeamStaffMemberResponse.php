<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Response;
use App\Modules\Identity\Domain\User\AccountUser;
use App\Modules\Staff\Domain\Staff\Staff;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRoleLabelCatalog;
final readonly class TeamStaffMemberResponse
{
    public function __construct(
        public string $assignmentId,
        public string $staffId,
        public string $userId,
        public ?string $fullName,
        public string $teamId,
        public string $role,
        public string $roleName,
    ) {}
    public static function fromEntities(TeamStaffAssignment $assignment, Staff $staff, ?AccountUser $user = null): self
    {
        return new self(
            $assignment->id()->value(),
            $staff->id()->value(),
            $staff->userId(),
            $user?->getFullName(),
            $assignment->teamId()->value(),
            $assignment->role()->value(),
            StaffRoleLabelCatalog::label($assignment->role()->value()),
        );
    }
    public function toArray(): array
    {
        return [
            'assignmentId' => $this->assignmentId,
            'staffId' => $this->staffId,
            'userId' => $this->userId,
            'fullName' => $this->fullName,
            'teamId' => $this->teamId,
            'role' => $this->role,
            'roleName' => $this->roleName,
        ];
    }
}
