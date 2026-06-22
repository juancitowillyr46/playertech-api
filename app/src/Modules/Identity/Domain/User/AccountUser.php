<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'users',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'UNIQ_1483A5E9E7927C74', columns: ['email'])],
    indexes: [
        new ORM\Index(name: 'IDX_USERS_ACADEMY_ID', columns: ['academy_id']),
        new ORM\Index(name: 'IDX_USERS_CREATED_BY', columns: ['created_by']),
        new ORM\Index(name: 'IDX_USERS_UPDATED_BY', columns: ['updated_by']),
        new ORM\Index(name: 'IDX_USERS_DELETED_BY', columns: ['deleted_by']),
    ]
)]
class AccountUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_INACTIVE = 'INACTIVE';
    public const STATUS_PENDING_ACTIVATION = 'PENDING_ACTIVATION';
    public const ROLE_ROOT = 'ROLE_ROOT';
    public const ROLE_ACADEMY_ADMIN = 'ROLE_ACADEMY_ADMIN';
    public const DEFAULT_ROLE = self::ROLE_ACADEMY_ADMIN;

    #[ORM\Id]
    #[ORM\Column(type: 'guid', name: 'id')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    #[ORM\Column(type: 'guid', name: 'academy_id', nullable: true)]
    private ?string $academyId = null;

    #[ORM\Column(type: 'string', length: 150, name: 'full_name', nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255, name: 'password_hash')]
    private string $passwordHash;

    #[ORM\Column(type: 'string', length: 50)]
    private string $role;

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

    #[ORM\Column(type: 'datetime_immutable', name: 'deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(type: 'guid', name: 'deleted_by', nullable: true)]
    private ?string $deletedBy = null;

    #[ORM\Column(type: 'string', length: 64, name: 'activation_token', nullable: true, unique: true)]
    private ?string $activationToken = null;

    #[ORM\Column(type: 'datetime_immutable', name: 'activation_expires_at', nullable: true)]
    private ?\DateTimeImmutable $activationExpiresAt = null;

    public function __construct()
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->role = self::DEFAULT_ROLE;
        $this->status = self::STATUS_ACTIVE;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return array_values(array_unique([$this->role, 'ROLE_USER']));
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIdValue(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getAcademyId(): ?string
    {
        return $this->academyId;
    }

    public function getAcademyIdValue(): ?Uuid
    {
        return null === $this->academyId ? null : Uuid::fromString($this->academyId);
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return self::STATUS_ACTIVE === $this->status;
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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function getActivationExpiresAt(): ?\DateTimeImmutable
    {
        return $this->activationExpiresAt;
    }

    public function setId(Uuid|string $id): void
    {
        $this->id = $id instanceof Uuid ? $id->toRfc4122() : $id;
    }

    public function setAcademyId(Uuid|string|null $academyId): void
    {
        $this->academyId = $academyId instanceof Uuid ? $academyId->toRfc4122() : $academyId;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = null === $fullName ? null : trim($fullName);
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->setPasswordHash($passwordHash);
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setCreatedBy(Uuid|string|null $createdBy): void
    {
        $this->createdBy = $createdBy instanceof Uuid ? $createdBy->toRfc4122() : $createdBy;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setUpdatedBy(Uuid|string|null $updatedBy): void
    {
        $this->updatedBy = $updatedBy instanceof Uuid ? $updatedBy->toRfc4122() : $updatedBy;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function setDeletedBy(Uuid|string|null $deletedBy): void
    {
        $this->deletedBy = $deletedBy instanceof Uuid ? $deletedBy->toRfc4122() : $deletedBy;
    }

    public function setActivationToken(?string $activationToken): void
    {
        $this->activationToken = $activationToken;
    }

    public function setActivationExpiresAt(?\DateTimeImmutable $activationExpiresAt): void
    {
        $this->activationExpiresAt = $activationExpiresAt;
    }

    public function markPendingActivation(string $activationToken, \DateTimeImmutable $expiresAt): void
    {
        $this->setStatus(self::STATUS_PENDING_ACTIVATION);
        $this->setActivationToken($activationToken);
        $this->setActivationExpiresAt($expiresAt);
    }

    public function activate(): void
    {
        $this->setStatus(self::STATUS_ACTIVE);
        $this->setActivationToken(null);
        $this->setActivationExpiresAt(null);
    }
}
