<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Command;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
final readonly class ChangeStaffRoleCommand { public function __construct(public string $actorId, public string $academyId, public string $assignmentId, public StaffRole $role) {}}
