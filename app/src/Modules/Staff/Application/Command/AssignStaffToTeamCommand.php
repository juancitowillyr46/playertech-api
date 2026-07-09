<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Command;
use App\Modules\Staff\Domain\TeamStaffAssignment\StaffRole;
final readonly class AssignStaffToTeamCommand { public function __construct(public string $actorId, public string $academyId, public string $staffId, public string $teamId, public StaffRole $role) {}}
