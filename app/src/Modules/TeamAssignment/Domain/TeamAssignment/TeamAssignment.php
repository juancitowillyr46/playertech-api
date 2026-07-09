<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Domain\TeamAssignment;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Domain\Team\TeamId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class TeamAssignment implements Auditable
{
    private TeamAssignmentId $id;
    private AcademyId $academyId;
    private PlayerId $playerId;
    private TeamId $teamId;
    private \DateTimeImmutable $startDate;
    private ?\DateTimeImmutable $endDate = null;
    private bool $isPrimary = false;
    private ?AuditTrail $auditTrail = null;
    private ?\DateTimeImmutable $deletedAt = null;
    private ?string $deletedBy = null;

    private function __construct(
        TeamAssignmentId $id,
        AcademyId $academyId,
        PlayerId $playerId,
        TeamId $teamId,
        \DateTimeImmutable $startDate,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->playerId = $playerId;
        $this->teamId = $teamId;
        $this->startDate = $startDate;
        $this->auditTrail = $auditTrail;
    }

    public static function create(TeamAssignmentId $id, AcademyId $academyId, PlayerId $playerId, TeamId $teamId, \DateTimeImmutable $startDate, AuditTrail $auditTrail): self
    {
        return new self($id, $academyId, $playerId, $teamId, $startDate, $auditTrail);
    }

    public function id(): TeamAssignmentId { return $this->id; }
    public function academyId(): AcademyId { return $this->academyId; }
    public function playerId(): PlayerId { return $this->playerId; }
    public function teamId(): TeamId { return $this->teamId; }
    public function startDate(): \DateTimeImmutable { return $this->startDate; }
    public function endDate(): ?\DateTimeImmutable { return $this->endDate; }
    public function isPrimary(): bool { return $this->isPrimary; }
    public function isActive(): bool { return null === $this->endDate; }
    public function auditTrail(): ?AuditTrail { return $this->auditTrail; }
    public function setAuditTrail(AuditTrail $auditTrail): void { $this->auditTrail = $auditTrail; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function deletedBy(): ?string { return $this->deletedBy; }

    public function markPrimary(string $updatedBy): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Only active assignments can be marked as primary.');
        }

        $this->isPrimary = true;
        $this->auditTrail?->touch($updatedBy);
    }

    public function unmarkPrimary(string $updatedBy): void
    {
        $this->isPrimary = false;
        $this->auditTrail?->touch($updatedBy);
    }

    public function finalize(\DateTimeImmutable $endedAt, string $updatedBy): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('The assignment is already finalized.');
        }

        $this->endDate = $endedAt;
        $this->isPrimary = false;
        $this->auditTrail?->touch($updatedBy);
    }
}
