<?php
declare(strict_types=1);
namespace App\Modules\Staff\Domain\Staff;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
final class Staff implements Auditable
{
    private StaffId $id; private AcademyId $academyId; private string $userId; private StaffStatus $status; private ?AuditTrail $auditTrail = null; private ?\DateTimeImmutable $deletedAt = null; private ?string $deletedBy = null;
    private function __construct(StaffId $id, AcademyId $academyId, string $userId, AuditTrail $auditTrail){ $this->id=$id; $this->academyId=$academyId; $this->userId=$userId; $this->status=StaffStatus::active(); $this->auditTrail=$auditTrail; }
    public static function create(StaffId $id, AcademyId $academyId, string $userId, AuditTrail $auditTrail): self { return new self($id,$academyId,$userId,$auditTrail); }
    public function id(): StaffId { return $this->id; } public function academyId(): AcademyId { return $this->academyId; } public function userId(): string { return $this->userId; } public function status(): StaffStatus { return $this->status; } public function auditTrail(): ?AuditTrail { return $this->auditTrail; } public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail=$auditTrail; } public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; } public function deletedBy(): ?string { return $this->deletedBy; }
}
