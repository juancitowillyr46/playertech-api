<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface TenantAwareInterface
{
    public function getAcademyId(): ?int;

    public function setAcademyId(int $academyId): void;
}
