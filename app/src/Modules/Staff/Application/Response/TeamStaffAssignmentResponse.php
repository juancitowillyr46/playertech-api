<?php
declare(strict_types=1);
namespace App\Modules\Staff\Application\Response;
use App\Modules\Staff\Domain\TeamStaffAssignment\TeamStaffAssignment;
final readonly class TeamStaffAssignmentResponse { public function __construct(public string $id, public string $teamId, public string $staffId, public string $role) {} public static function fromAssignment(TeamStaffAssignment $assignment): self { return new self($assignment->id()->value(), $assignment->teamId()->value(), $assignment->staffId()->value(), $assignment->role()->value()); } public function toArray(): array { return ['id'=>$this->id,'teamId'=>$this->teamId,'staffId'=>$this->staffId,'role'=>$this->role]; } }
