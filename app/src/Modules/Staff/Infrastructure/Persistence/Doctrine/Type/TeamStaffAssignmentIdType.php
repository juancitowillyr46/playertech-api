<?php
declare(strict_types=1);
namespace App\Modules\Staff\Infrastructure\Persistence\Doctrine\Type;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignmentId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractUuidType;
final class TeamStaffAssignmentIdType extends AbstractUuidType { public const NAME='team_staff_assignment_id'; protected function getValueObjectClass(): string { return TeamStaffAssignmentId::class; } public function getName(): string { return self::NAME; } }
