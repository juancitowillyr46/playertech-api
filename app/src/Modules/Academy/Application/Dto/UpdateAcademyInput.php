<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

final readonly class UpdateAcademyInput
{
    public function __construct(
        public ?string $name,

        public ?string $contactEmail,

        public ?string $phone = null,

        public ?string $country = null,

        public ?string $department = null,

        public ?string $taxIdType = null,

        public ?string $taxIdNumber = null,

        public ?string $taxCheckDigit = null,

        public ?string $taxRegime = null,

        public ?string $billingEmail = null,

        public ?string $address = null,

        public ?string $city = null,
    ) {
    }
}
