<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Shared\Application\Response\MediaResponse;

final readonly class AcademyResponse
{
    private function __construct(
        private string $id,
        private string $name,
        private string $contactEmail,
        private ?string $phone,
        private ?string $address,
        private ?string $city,
        private ?MediaResponse $logo,
        private string $status,
        private AcademyAuditResponse $audit,
    ) {
    }

    public static function fromAcademy(Academy $academy): self
    {
        return new self(
            $academy->id()->value(),
            $academy->name()->value(),
            $academy->contactEmail()->value(),
            $academy->phone()?->value(),
            $academy->address()?->value(),
            $academy->city()?->value(),
            null === $academy->logo() ? null : MediaResponse::fromPath($academy->logo()->value()),
            $academy->status()->value(),
            AcademyAuditResponse::fromAuditTrail($academy->auditTrail()),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contact_email' => $this->contactEmail,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'logo' => $this->logo?->toArray(),
            'status' => $this->status,
            'audit' => $this->audit->toArray(),
        ];
    }
}
