<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Media;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;

final class Academy implements Auditable
{
    private AcademyId $id;

    private Name $name;

    private Email $contactEmail;

    private ?PhoneNumber $phone;

    private ?string $country;

    private ?string $department;

    private ?string $taxIdType;

    private ?string $taxIdNumber;

    private ?string $taxRegime;

    private ?string $billingEmail;

    private ?string $taxCheckDigit;

    private string $registrationSource;

    private ?Address $address;

    private ?City $city;

    private ?Media $shield;

    private AcademyStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        AcademyId $id,
        Name $name,
        Email $contactEmail,
        ?PhoneNumber $phone,
        ?string $country,
        ?string $department,
        ?string $taxIdType,
        ?string $taxIdNumber,
        ?string $taxRegime,
        ?string $billingEmail,
        string $registrationSource,
        ?Address $address,
        ?City $city,
        ?Media $shield,
        AuditTrail $auditTrail,
        ?string $taxCheckDigit = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->contactEmail = $contactEmail;
        $this->status = AcademyStatus::active();
        $this->phone = $phone;
        $this->country = $country;
        $this->department = $department;
        $this->taxIdType = $taxIdType;
        $this->taxIdNumber = $taxIdNumber;
        $this->taxRegime = $taxRegime;
        $this->billingEmail = $billingEmail;
        $this->taxCheckDigit = self::normalizeNullableText($taxCheckDigit);
        $this->registrationSource = $registrationSource;
        $this->address = $address;
        $this->city = $city;
        $this->shield = $shield;
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        AcademyId $id,
        Name $name,
        Email $contactEmail,
        ?PhoneNumber $phone,
        ?string $country,
        ?string $department,
        ?string $taxIdType,
        ?string $taxIdNumber,
        ?string $taxRegime,
        ?string $billingEmail,
        string $registrationSource,
        ?Address $address,
        ?City $city,
        ?Media $shield,
        AuditTrail $auditTrail,
        ?string $taxCheckDigit = null
    ): self {
        return new self(
            $id,
            $name,
            $contactEmail,
            $phone,
            $country,
            $department,
            $taxIdType,
            $taxIdNumber,
            $taxRegime,
            $billingEmail,
            $registrationSource,
            $address,
            $city,
            $shield,
            $auditTrail,
            $taxCheckDigit
        );
    }

    public function id(): AcademyId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function contactEmail(): Email
    {
        return $this->contactEmail;
    }

    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function country(): ?string
    {
        return $this->country;
    }

    public function department(): ?string
    {
        return $this->department;
    }

    public function taxIdType(): ?string
    {
        return $this->taxIdType;
    }

    public function taxIdNumber(): ?string
    {
        return $this->taxIdNumber;
    }

    public function taxRegime(): ?string
    {
        return $this->taxRegime;
    }

    public function billingEmail(): ?string
    {
        return $this->billingEmail;
    }

    public function taxCheckDigit(): ?string
    {
        return $this->taxCheckDigit;
    }

    public function registrationSource(): string
    {
        return $this->registrationSource;
    }

    public function address(): ?Address
    {
        return $this->address;
    }

    public function city(): ?City
    {
        return $this->city;
    }

    public function shield(): ?Media
    {
        return $this->shield;
    }

    public function status(): AcademyStatus
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

    public function updateProfile(
        Name $name,
        Email $contactEmail,
        ?PhoneNumber $phone,
        ?string $country,
        ?string $department,
        ?string $taxIdType,
        ?string $taxIdNumber,
        ?string $taxCheckDigit,
        ?string $taxRegime,
        ?string $billingEmail,
        string $registrationSource,
        ?Address $address,
        ?City $city,
        string $updatedBy
    ): void {
        $this->name = $name;
        $this->contactEmail = $contactEmail;
        $this->phone = $phone;
        $this->country = $country;
        $this->department = $department;
        $this->taxIdType = $taxIdType;
        $this->taxIdNumber = $taxIdNumber;
        $this->taxCheckDigit = self::normalizeNullableText($taxCheckDigit);
        $this->taxRegime = $taxRegime;
        $this->billingEmail = $billingEmail;
        $this->registrationSource = $registrationSource;
        $this->address = $address;
        $this->city = $city;
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function updateShield(?Media $newShield, string $updatedBy): void
    {
        $this->shield = $newShield;
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function updateTaxProfile(
        ?string $taxIdType,
        ?string $taxIdNumber,
        ?string $taxCheckDigit,
        ?string $taxRegime,
        ?string $billingEmail,
        string $updatedBy
    ): void {
        $this->taxIdType = $taxIdType;
        $this->taxIdNumber = $taxIdNumber;
        $this->taxCheckDigit = self::normalizeNullableText($taxCheckDigit);
        $this->taxRegime = $taxRegime;
        $this->billingEmail = $billingEmail;
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function suspend(string $updatedBy): void
    {
        if ($this->status->isSuspended()) {
            return;
        }

        $this->status = AcademyStatus::suspended();
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function reactivate(string $updatedBy): void
    {
        if ($this->status->isActive()) {
            return;
        }

        $this->status = AcademyStatus::active();
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

    private static function normalizeNullableText(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $normalized = trim($value);

        return '' === $normalized ? null : $normalized;
    }
}
