<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\LegalGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;

final class LegalGuardian implements Auditable
{
    private LegalGuardianId $id;

    private AcademyId $academyId;

    private string $firstName;

    private string $lastName;

    private ?string $phone;

    private ?string $email;

    private LegalGuardianStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        LegalGuardianId $id,
        AcademyId $academyId,
        string $firstName,
        string $lastName,
        ?string $phone,
        ?string $email,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->firstName = self::normalizeText($firstName, 'first name');
        $this->lastName = self::normalizeText($lastName, 'last name');
        $this->phone = self::normalizeNullableText($phone);
        $this->email = self::normalizeNullableEmail($email);
        $this->status = LegalGuardianStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        LegalGuardianId $id,
        AcademyId $academyId,
        string $firstName,
        string $lastName,
        ?string $phone,
        ?string $email,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $firstName,
            $lastName,
            $phone,
            $email,
            $auditTrail
        );
    }

    public function id(): LegalGuardianId
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

    public function phone(): ?string
    {
        return $this->phone;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function status(): LegalGuardianStatus
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

    private static function normalizeText(string $value, string $field): string
    {
        $value = trim($value);

        if ('' === $value) {
            throw new \InvalidArgumentException(sprintf('%s cannot be empty.', $field));
        }

        return $value;
    }

    private static function normalizeNullableText(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim($value);

        return '' === $value ? null : $value;
    }

    private static function normalizeNullableEmail(?string $value): ?string
    {
        $value = self::normalizeNullableText($value);

        if (null === $value) {
            return null;
        }

        $value = mb_strtolower($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email is invalid.');
        }

        return $value;
    }
}
