<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Response;

use App\Modules\Academy\Domain\Academy\Academy;

final readonly class AcademyTaxProfileResponse
{
    private function __construct(
        private string $academyId,
        private ?string $taxIdType,
        private ?string $taxIdNumber,
        private ?string $taxRegime,
        private ?string $billingEmail,
    ) {
    }

    public static function fromAcademy(Academy $academy): self
    {
        return new self(
            $academy->id()->value(),
            $academy->taxIdType(),
            $academy->taxIdNumber(),
            $academy->taxRegime(),
            $academy->billingEmail(),
        );
    }

    public function toArray(): array
    {
        return [
            'academyId' => $this->academyId,
            'taxIdType' => $this->taxIdType,
            'taxIdNumber' => $this->taxIdNumber,
            'taxRegime' => $this->taxRegime,
            'billingEmail' => $this->billingEmail,
        ];
    }
}
