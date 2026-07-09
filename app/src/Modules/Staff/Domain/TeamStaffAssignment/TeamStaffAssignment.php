<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\TeamStaffAssignment;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Staff\Domain\Staff\StaffId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class TeamStaffAssignment implements Auditable
{
    private TeamStaffAssignmentId $id; private AcademyId $academyId; private StaffId $staffId; private TeamId $teamId; private StaffRole $role; private ?AuditTrail $auditTrail=null; private ?\DateTimeImmutable $deletedAt=null; private ?string $deletedBy=null;
    private function __construct(TeamStaffAssignmentId $id, AcademyId $academyId, StaffId $staffId, TeamId $teamId, StaffRole $role, AuditTrail $auditTrail){ $this->id=$id;$this->academyId=$academyId;$this->staffId=$staffId;$this->teamId=$teamId;$this->role=$role;$this->auditTrail=$auditTrail; }
    public static function create(TeamStaffAssignmentId $id, AcademyId $academyId, StaffId $staffId, TeamId $teamId, StaffRole $role, AuditTrail $auditTrail): self { return new self($id,$academyId,$staffId,$teamId,$role,$auditTrail); }
    public function id(): TeamStaffAssignmentId { return $this->id; } public function academyId(): AcademyId { return $this->academyId; } public function staffId(): StaffId { return $this->staffId; } public function teamId(): TeamId { return $this->teamId; } public function role(): StaffRole { return $this->role; } public function auditTrail(): ?AuditTrail { return $this->auditTrail; } public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail=$auditTrail; } public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; } public function deletedBy(): ?string { return $this->deletedBy; }
    public function changeRole(StaffRole $role, string $updatedBy): void { $this->role=$role; if($this->auditTrail){$this->auditTrail->touch($updatedBy);} }
    public function remove(string $deletedBy): void { $this->deletedAt=new \DateTimeImmutable(); $this->deletedBy=$deletedBy; if($this->auditTrail){$this->auditTrail->touch($deletedBy);} }
}
