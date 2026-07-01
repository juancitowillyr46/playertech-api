<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Player;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class Player implements Auditable
{
    private PlayerId $id;

    private AcademyId $academyId;

    private string $firstName;

    private string $lastName;

    private \DateTimeImmutable $birthDate;

    private string $documentNumber;

    private ?CategoryId $categoryId;

    private PlayerStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        PlayerId $id,
        AcademyId $academyId,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        ?CategoryId $categoryId,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->firstName = self::normalizeText($firstName, 'first name');
        $this->lastName = self::normalizeText($lastName, 'last name');
        $this->birthDate = $birthDate;
        $this->documentNumber = self::normalizeText($documentNumber, 'document number');
        $this->categoryId = $categoryId;
        $this->status = PlayerStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        PlayerId $id,
        AcademyId $academyId,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        ?CategoryId $categoryId,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $firstName,
            $lastName,
            $birthDate,
            $documentNumber,
            $categoryId,
            $auditTrail
        );
    }

    public function id(): PlayerId
    {
        return $this->id;
    }

    public function academyId(): AcademyId
    {
        return $this->academyId;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function birthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function documentNumber(): string
    {
        return $this->documentNumber;
    }

    public function categoryId(): ?CategoryId
    {
        return $this->categoryId;
    }

    public function status(): PlayerStatus
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

    public function updateProfile(
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        string $updatedBy,
    ): void {
        $this->firstName = self::normalizeText($firstName, 'first name');
        $this->lastName = self::normalizeText($lastName, 'last name');
        $this->birthDate = $birthDate;
        $this->documentNumber = self::normalizeText($documentNumber, 'document number');
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function inactivate(string $updatedBy): void
    {
        $this->status = PlayerStatus::inactive();
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function activate(string $updatedBy): void
    {
        $this->status = PlayerStatus::active();
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
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

    private static function normalizeText(string $value, string $field): string
    {
        $value = trim($value);

        if ('' === $value) {
            throw new \InvalidArgumentException(sprintf('%s cannot be empty.', $field));
        }

        return $value;
    }
}
