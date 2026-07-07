<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Team;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;

final class Team implements Auditable
{
    private TeamId $id;

    private AcademyId $academyId;

    private CategoryId $categoryId;

    private Name $name;

    private TeamStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        TeamId $id,
        AcademyId $academyId,
        CategoryId $categoryId,
        Name $name,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->status = TeamStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        TeamId $id,
        AcademyId $academyId,
        CategoryId $categoryId,
        Name $name,
        AuditTrail $auditTrail
    ): self {
        return new self($id, $academyId, $categoryId, $name, $auditTrail);
    }

    public function id(): TeamId
    {
        return $this->id;
    }

    public function academyId(): AcademyId
    {
        return $this->academyId;
    }

    public function categoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function status(): TeamStatus
    {
        return $this->status;
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

    public function update(
        CategoryId $categoryId,
        Name $name,
        string $updatedBy
    ): void {
        $this->categoryId = $categoryId;
        $this->name = $name;

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function inactivate(string $updatedBy): void
    {
        if ($this->status->isInactive()) {
            return;
        }

        $this->status = TeamStatus::inactive();

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function activate(string $updatedBy): void
    {
        if ($this->status->isActive()) {
            return;
        }

        $this->status = TeamStatus::active();

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

    public function restore(string $updatedBy): void
    {
        if (null === $this->deletedAt) {
            return;
        }

        $this->deletedAt = null;
        $this->deletedBy = null;

        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }
}
