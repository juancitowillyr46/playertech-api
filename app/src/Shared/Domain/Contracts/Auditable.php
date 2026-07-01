<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

use App\Shared\Domain\ValueObject\AuditTrail;

interface Auditable
{
    public function auditTrail(): ?AuditTrail;

    public function setAuditTrail(AuditTrail $auditTrail): void;
}
