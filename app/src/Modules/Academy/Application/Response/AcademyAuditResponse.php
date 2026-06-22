<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class AcademyAuditResponse
{
    public function __construct(
        private string $createdAt,
        private ?string $createdBy,
        private ?string $updatedAt,
        private ?string $updatedBy,
    ) {
    }

    public static function fromAuditTrail(AuditTrail $auditTrail): self
    {
        return new self(
            $auditTrail->createdAt()->value()->format(DATE_ATOM),
            $auditTrail->createdBy(),
            $auditTrail->updatedAt()?->value()?->format(DATE_ATOM),
            $auditTrail->updatedBy(),
        );
    }

    public function toArray(): array
    {
        return [
            'created_at' => $this->createdAt,
            'created_by' => $this->createdBy,
            'updated_at' => $this->updatedAt,
            'updated_by' => $this->updatedBy,
        ];
    }
}
