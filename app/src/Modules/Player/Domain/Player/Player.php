<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Player;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Shared\Domain\Contracts\Auditable;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Media;

final class Player implements Auditable
{
    private PlayerId $id;

    private AcademyId $academyId;

    private string $documentType;

    private string $firstName;

    private string $lastName;

    private \DateTimeImmutable $birthDate;

    private string $documentNumber;

    private ?string $email;

    private ?string $phone;

    private ?string $nationality;

    private ?string $gender;

    private ?string $federationId;

    private ?string $dominantFoot;

    private ?CategoryId $categoryId;

    private ?Media $photo;

    private PlayerStatus $status;

    private ?AuditTrail $auditTrail = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private ?string $deletedBy = null;

    private function __construct(
        PlayerId $id,
        AcademyId $academyId,
        string $documentType,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        ?string $email,
        ?string $phone,
        ?string $nationality,
        ?string $gender,
        ?string $federationId,
        ?string $dominantFoot,
        ?CategoryId $categoryId,
        ?Media $photo,
        AuditTrail $auditTrail
    ) {
        $this->id = $id;
        $this->academyId = $academyId;
        $this->documentType = self::normalizeText($documentType, 'document type');
        $this->firstName = self::normalizeText($firstName, 'first name');
        $this->lastName = self::normalizeText($lastName, 'last name');
        $this->birthDate = $birthDate;
        $this->documentNumber = self::normalizeText($documentNumber, 'document number');
        $this->email = self::normalizeNullableText($email);
        $this->phone = self::normalizeNullableText($phone);
        $this->nationality = self::normalizeNullableText($nationality);
        $this->gender = self::normalizeNullableText($gender);
        $this->federationId = self::normalizeNullableText($federationId);
        $this->dominantFoot = self::normalizeNullableText($dominantFoot);
        $this->categoryId = $categoryId;
        $this->photo = $photo;
        $this->status = PlayerStatus::active();
        $this->auditTrail = $auditTrail;
    }

    public static function create(
        PlayerId $id,
        AcademyId $academyId,
        string $documentType,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        ?string $email,
        ?string $phone,
        ?string $nationality,
        ?string $gender,
        ?string $federationId,
        ?string $dominantFoot,
        ?CategoryId $categoryId,
        ?Media $photo,
        AuditTrail $auditTrail
    ): self {
        return new self(
            $id,
            $academyId,
            $documentType,
            $firstName,
            $lastName,
            $birthDate,
            $documentNumber,
            $email,
            $phone,
            $nationality,
            $gender,
            $federationId,
            $dominantFoot,
            $categoryId,
            $photo,
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

    public function documentType(): string
    {
        return $this->documentType;
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

    public function email(): ?string
    {
        return $this->email;
    }

    public function phone(): ?string
    {
        return $this->phone;
    }

    public function nationality(): ?string
    {
        return $this->nationality;
    }

    public function gender(): ?string
    {
        return $this->gender;
    }

    public function federationId(): ?string
    {
        return $this->federationId;
    }

    public function dominantFoot(): ?string
    {
        return $this->dominantFoot;
    }

    public function categoryId(): ?CategoryId
    {
        return $this->categoryId;
    }

    public function photo(): ?Media
    {
        return $this->photo;
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
        string $documentType,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $documentNumber,
        ?string $email,
        ?string $phone,
        ?string $nationality,
        ?string $gender,
        ?string $federationId,
        ?string $dominantFoot,
        string $updatedBy,
    ): void {
        $this->documentType = self::normalizeText($documentType, 'document type');
        $this->firstName = self::normalizeText($firstName, 'first name');
        $this->lastName = self::normalizeText($lastName, 'last name');
        $this->birthDate = $birthDate;
        $this->documentNumber = self::normalizeText($documentNumber, 'document number');
        $this->email = self::normalizeNullableText($email);
        $this->phone = self::normalizeNullableText($phone);
        $this->nationality = self::normalizeNullableText($nationality);
        $this->gender = self::normalizeNullableText($gender);
        $this->federationId = self::normalizeNullableText($federationId);
        $this->dominantFoot = self::normalizeNullableText($dominantFoot);
        if ($this->auditTrail) {
            $this->auditTrail->touch($updatedBy);
        }
    }

    public function updatePhoto(?Media $photo, string $updatedBy): void
    {
        $this->photo = $photo;
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

    private static function normalizeNullableText(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim($value);

        return '' === $value ? null : $value;
    }
}
