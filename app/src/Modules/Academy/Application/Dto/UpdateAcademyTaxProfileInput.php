<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

final readonly class UpdateAcademyTaxProfileInput
{
    public function __construct(
        public ?string $taxIdType = null,
        public ?string $taxIdNumber = null,
        public ?string $taxRegime = null,
        public ?string $billingEmail = null,
    ) {
    }
}
