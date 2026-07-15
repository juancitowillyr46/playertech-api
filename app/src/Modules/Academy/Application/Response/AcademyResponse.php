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
        private ?string $country,
        private ?string $department,
        private ?string $taxIdType,
        private ?string $taxIdNumber,
        private ?string $taxRegime,
        private ?string $billingEmail,
        private string $registrationSource,
        private ?string $address,
        private ?string $city,
        private ?MediaResponse $shield,
        private string $status,
        private AcademyAuditResponse $audit,
    ) {
    }

    public static function fromAcademy(Academy $academy): self
    {
        $shield = $academy->shield();

        return new self(
            $academy->id()->value(),
            $academy->name()->value(),
            $academy->contactEmail()->value(),
            $academy->phone()?->value(),
            $academy->country(),
            $academy->department(),
            $academy->taxIdType(),
            $academy->taxIdNumber(),
            $academy->taxRegime(),
            $academy->billingEmail(),
            $academy->registrationSource(),
            $academy->address()?->value(),
            $academy->city()?->value(),
            null === $shield ? null : MediaResponse::fromDetails(
                $shield->path(),
                $shield->url(),
                $shield->mimeType(),
                $shield->size(),
                $shield->checksum(),
            ),
            $academy->status()->value(),
            AcademyAuditResponse::fromAuditTrail($academy->auditTrail()),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contactEmail' => $this->contactEmail,
            'phone' => $this->phone,
            'country' => $this->country,
            'department' => $this->department,
            'taxIdType' => $this->taxIdType,
            'taxIdNumber' => $this->taxIdNumber,
            'taxRegime' => $this->taxRegime,
            'billingEmail' => $this->billingEmail,
            'registrationSource' => $this->registrationSource,
            'address' => $this->address,
            'city' => $this->city,
            'shield' => $this->shield?->toArray(),
            'status' => $this->status,
            'audit' => $this->audit->toArray(),
        ];
    }
}
