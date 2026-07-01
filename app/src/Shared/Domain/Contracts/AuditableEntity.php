<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface AuditableEntity
{
    public function setCreatedAt(\DateTimeImmutable $createdAt): void;

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void;

    public function setCreatedBy(?string $createdBy): void;

    public function setUpdatedBy(?string $updatedBy): void;
}
