<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Venue;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Shared\Domain\ValueObject\Notes;

final class Venue
{
    private VenueId $id;

    private AcademyId $academyId;

    private Name $name;

    private ?Address $address;

    private ?City $city;

    private ?PhoneNumber $phone;

    private ?Notes $notes;

    private VenueStatus $status;

    private AuditTrail $auditTrail;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        VenueId $id,
        AcademyId $academyId,
        Name $name,
        ?Address $address,
        ?City $city,
        ?PhoneNumber $phone,
        ?Notes $notes,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->phone = $phone;
        $this->notes = $notes;
        $this->status = VenueStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        VenueId $id,
        AcademyId $academyId,
        Name $name,
        ?Address $address,
        ?City $city,
        ?PhoneNumber $phone,
        ?Notes $notes,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $name,
            $address,
            $city,
            $phone,
            $notes,
            $auditTrail
        );
    }

    public function id(): VenueId
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

    public function address(): ?Address
    {
        return $this->address;
    }

    public function city(): ?City
    {
        return $this->city;
    }

    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function notes(): ?Notes
    {
        return $this->notes;
    }

    public function status(): VenueStatus
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
}