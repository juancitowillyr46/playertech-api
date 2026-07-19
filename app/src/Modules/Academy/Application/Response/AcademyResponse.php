<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Academy\Domain\Academy\Academy;
use App\Shared\Application\Response\MediaResponse;
use App\Shared\Domain\ValueObject\Media;

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
        private ?string $taxCheckDigit,
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
            $academy->taxCheckDigit(),
            $academy->taxRegime(),
            $academy->billingEmail(),
            $academy->registrationSource(),
            $academy->address()?->value(),
            $academy->city()?->value(),
            self::buildShieldResponse($shield),
            $academy->status()->value(),
            AcademyAuditResponse::fromAuditTrail($academy->auditTrail()),
        );
    }

    private static function buildShieldResponse(?Media $shield): ?MediaResponse
    {
        if (null === $shield) {
            return null;
        }

        $reflection = new \ReflectionObject($shield);
        foreach (['path', 'url', 'mimeType', 'size', 'checksum'] as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if (!$property->isInitialized($shield)) {
                return null;
            }
        }

        return MediaResponse::fromDetails(
                $shield->path(),
                $shield->url(),
                $shield->mimeType(),
                $shield->size(),
                $shield->checksum(),
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
            'taxCheckDigit' => $this->taxCheckDigit,
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
