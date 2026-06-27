<?php

declare(strict_types=1);

namespace App\Modules\Category\Domain\Category;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\Age;
use App\Shared\Domain\ValueObject\AgeFrom;
use App\Shared\Domain\ValueObject\AgeTo;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Description;
use App\Shared\Domain\ValueObject\MaximumAge;
use App\Shared\Domain\ValueObject\MinimumAge;
use App\Shared\Domain\ValueObject\Name;

final class Category
{
    private CategoryId $id;

    private AcademyId $academyId;

    private Name $name;

    private MinimumAge $minAge;

    private MaximumAge $maxAge;

    private ?Description $description;

    private CategoryStatus $status;

    private AuditTrail $auditTrail;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        CategoryId $id,
        AcademyId $academyId,
        Name $name,
        MinimumAge $minAge,
        MaximumAge $maxAge,
        ?Description $description,
        AuditTrail $auditTrail
    ) {
        if ($minAge->value() >= $maxAge->value()) {
            throw new \InvalidArgumentException(
                'Minimum age must be lower than maximum age.'
            );
        }

        $this->id = $id;
        $this->academyId = $academyId;
        $this->name = $name;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->description = $description;
        $this->status = CategoryStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        CategoryId $id,
        AcademyId $academyId,
        Name $name,
        MinimumAge $minAge,
        MaximumAge $maxAge,
        ?Description $description,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $name,
            $minAge,
            $maxAge,
            $description,
            $auditTrail
        );
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function academyId(): AcademyId
    {
        return $this->academyId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function minAge(): MinimumAge
    {
        return $this->minAge;
    }

    public function maxAge(): MaximumAge
    {
        return $this->maxAge;
    }

    public function description(): ?Description
    {
        return $this->description;
    }

    public function status(): CategoryStatus
    {
        return $this->status;
    }

    public function auditTrail(): AuditTrail
    {
        return $this->auditTrail;
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
        Name $name,
        MinimumAge $minAge,
        MaximumAge $maxAge,
        ?Description $description,
        string $updatedBy
    ): void {
        if ($minAge->value() >= $maxAge->value()) {
            throw new \InvalidArgumentException(
                'Minimum age must be lower than maximum age.'
            );
        }

        $this->name = $name;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->description = $description;

        $this->auditTrail->touch($updatedBy);
    }

    public function deactivate(string $updatedBy): void
    {
        if ($this->status->isInactive()) {
            return;
        }

        $this->status = CategoryStatus::inactive();

        $this->auditTrail->touch($updatedBy);
    }

    public function activate(string $updatedBy): void
    {
        if ($this->status->isActive()) {
            return;
        }

        $this->status = CategoryStatus::active();

        $this->auditTrail->touch($updatedBy);
    }

    public function delete(string $deletedBy): void
    {
        if (null !== $this->deletedAt) {
            return;
        }

        $this->deletedAt = new \DateTimeImmutable();
        $this->deletedBy = $deletedBy;

        $this->auditTrail->touch($deletedBy);
    }

    public function restore(string $updatedBy): void
    {
        if (null === $this->deletedAt) {
            return;
        }

        $this->deletedAt = null;
        $this->deletedBy = null;

        $this->auditTrail->touch($updatedBy);
    }
}