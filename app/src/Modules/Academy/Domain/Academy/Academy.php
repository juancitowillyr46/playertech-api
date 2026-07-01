<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\LogoPath;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;

final class Academy implements Auditable
{
    private AcademyId $id;

    private Name $name;

    private Email $contactEmail;

    private ?PhoneNumber $phone;

    private ?Address $address;

    private ?City $city;

    private ?LogoPath $logo;

    private AcademyStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        AcademyId $id,
        Name $name,
        Email $contactEmail,
        ?PhoneNumber $phone,
        ?Address $address,
        ?City $city,
        ?LogoPath $logo,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->contactEmail = $contactEmail;
        $this->status = AcademyStatus::active();
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->logo = $logo;
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        AcademyId $id,
        Name $name,
        Email $contactEmail,
        ?PhoneNumber $phone,
        ?Address $address,
        ?City $city,
        ?LogoPath $logo,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $name,
            $contactEmail,
            $phone,
            $address,
            $city,
            $logo,
            $auditTrail
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

    public function address(): ?Address
    {
        return $this->address;
    }

    public function city(): ?City
    {
        return $this->city;
    }

    public function logo(): ?LogoPath
    {
        return $this->logo;
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
        ?Address $address,
        ?City $city,
        ?LogoPath $logo,
        string $updatedBy
    ): void {
        $this->name = $name;
        $this->contactEmail = $contactEmail;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->logo = $logo;
        // if ($this->auditTrail) {
        //     $this->auditTrail->touch($updatedBy);
        // }
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
}
