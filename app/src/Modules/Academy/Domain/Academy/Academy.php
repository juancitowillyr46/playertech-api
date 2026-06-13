<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

use App\Modules\Academy\Infrastructure\Persistence\AcademyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AcademyRepository::class)]
#[ORM\Table(
    name: 'academies',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'UNIQ_ACADEMIES_CONTACT_EMAIL', columns: ['contact_email'])],
    indexes: [
        new ORM\Index(name: 'IDX_ACADEMIES_STATUS', columns: ['status']),
        new ORM\Index(name: 'IDX_ACADEMIES_CREATED_BY', columns: ['created_by']),
        new ORM\Index(name: 'IDX_ACADEMIES_UPDATED_BY', columns: ['updated_by']),
    ]
)]
class Academy
{
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    #[ORM\Id]
    #[ORM\Column(type: 'guid', name: 'id')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    #[ORM\Column(type: 'string', length: 150)]
    private string $name;

    #[ORM\Column(type: 'string', length: 180, name: 'contact_email')]
    private string $contactEmail;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: 'string', length: 120, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'guid', name: 'created_by', nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'guid', name: 'updated_by', nullable: true)]
    private ?string $updatedBy = null;

    public function __construct()
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->status = self::STATUS_ACTIVE;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return self::STATUS_ACTIVE === $this->status;
    }

    public function isSuspended(): bool
    {
        return self::STATUS_SUSPENDED === $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedBy(?string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setUpdatedBy(?string $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function updateProfile(
        string $name,
        string $contactEmail,
        ?string $phone,
        ?string $address,
        ?string $city,
        ?string $logo,
        string $updatedBy
    ): void {
        $this->name = $name;
        $this->contactEmail = $contactEmail;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->logo = $logo;
        $this->updatedAt = new \DateTimeImmutable();
        $this->updatedBy = $updatedBy;
    }

    public function suspend(string $updatedBy): void
    {
        $this->status = self::STATUS_SUSPENDED;
        $this->updatedAt = new \DateTimeImmutable();
        $this->updatedBy = $updatedBy;
    }

    public function reactivate(string $updatedBy): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->updatedAt = new \DateTimeImmutable();
        $this->updatedBy = $updatedBy;
    }
}
