<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\PlayerGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class PlayerGuardian implements Auditable
{
    private PlayerGuardianId $id;

    private AcademyId $academyId;

    private PlayerId $playerId;

    private LegalGuardianId $guardianId;

    private bool $isPrimary;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        PlayerGuardianId $id,
        AcademyId $academyId,
        PlayerId $playerId,
        LegalGuardianId $guardianId,
        bool $isPrimary,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->playerId = $playerId;
        $this->guardianId = $guardianId;
        $this->isPrimary = $isPrimary;
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        PlayerGuardianId $id,
        AcademyId $academyId,
        PlayerId $playerId,
        LegalGuardianId $guardianId,
        bool $isPrimary,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $playerId,
            $guardianId,
            $isPrimary,
            $auditTrail
        );
    }

    public function id(): PlayerGuardianId
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

    public function guardianId(): LegalGuardianId
    {
        return $this->guardianId;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function auditTrail(): ?AuditTrail
    {
        return $this->auditTrail;
    }

    public function setAuditTrail(AuditTrail $auditTrail): void
    {
        $this->auditTrail = $auditTrail;
    }

    public function promote(string $updatedBy): void
    {
        $this->isPrimary = true;

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function demote(string $updatedBy): void
    {
        $this->isPrimary = false;

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function delete(string $deletedBy): void
    {
        if (null !== $this->deletedAt) {
            return;
        }

        $this->deletedAt = new \DateTimeImmutable();
        $this->deletedBy = $deletedBy;

        if ($this->auditTrail) {
            $this->auditTrail->touch($deletedBy);
        }
    }

    public function deletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function deletedBy(): ?string
    {
        return $this->deletedBy;
    }
}
