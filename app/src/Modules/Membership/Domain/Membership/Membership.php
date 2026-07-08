<?php

declare(strict_types=1);

namespace App\Modules\Membership\Domain\Membership;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class Membership implements Auditable
{
    private MembershipId $id;

    private AcademyId $academyId;

    private PlayerId $playerId;

    private LegalGuardianId $primaryGuardianId;

    private MembershipStatus $status;

    private \DateTimeImmutable $startedAt;

    private ?\DateTimeImmutable $endedAt = null;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        MembershipId $id,
        AcademyId $academyId,
        PlayerId $playerId,
        LegalGuardianId $primaryGuardianId,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->playerId = $playerId;
        $this->primaryGuardianId = $primaryGuardianId;
        $this->status = MembershipStatus::active();
        $this->startedAt = new \DateTimeImmutable();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        MembershipId $id,
        AcademyId $academyId,
        PlayerId $playerId,
        LegalGuardianId $primaryGuardianId,
        AuditTrail $auditTrail
    ): self {
        return new self($id, $academyId, $playerId, $primaryGuardianId, $auditTrail);
    }

    public function id(): MembershipId
    {
        return $this->id;
    }

    public function academyId(): AcademyId
    {
        return $this->academyId;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    public function primaryGuardianId(): LegalGuardianId
    {
        return $this->primaryGuardianId;
    }

    public function status(): MembershipStatus
    {
        return $this->status;
    }

    public function startedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function endedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function auditTrail(): ?AuditTrail
    {
        return $this->auditTrail;
    }

    public function setAuditTrail(AuditTrail $auditTrail): void
    {
        $this->auditTrail = $auditTrail;
    }

    public function deletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function deletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function suspend(string $updatedBy): void
    {
        $this->status = MembershipStatus::suspended();
        $this->endedAt = new \DateTimeImmutable();

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function withdraw(string $updatedBy): void
    {
        $this->status = MembershipStatus::withdrawn();
        $this->endedAt = new \DateTimeImmutable();

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }
}
