<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class AuditTrail
{
    public function __construct(
        private CreatedAt $createdAt,
        private ?string $createdBy,
        private ?UpdatedAt $updatedAt,
        private ?string $updatedBy
    ) {
    }

    public static function create(?string $userId): self
    {
        return new self(
            CreatedAt::now(),
            $userId,
            null,
            null
        );
    }

    public function touch(string $userId): void
    {
        $this->updatedAt = new UpdatedAt(
            new \DateTimeImmutable()
        );

        $this->updatedBy = $userId;
    }

    public function createdAt(): CreatedAt
    {
        return $this->createdAt;
    }

    public function createdBy(): ?string
    {
        return $this->createdBy;
    }

    public function updatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }

    public function updatedBy(): ?string
    {
        return $this->updatedBy;
    }
}
