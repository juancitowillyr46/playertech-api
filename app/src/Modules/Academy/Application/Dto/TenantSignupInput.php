<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

final readonly class TenantSignupInput
{
    public function __construct(
        public string $name,

        public string $contactEmail,

        public string $contactName,

        public string $password,

        public string $phone,

        public string $country,

        public string $department,

        public string $city,

        public string $address,

        public string $onboardingCategoryId,

        public string $teamName,

        public bool $acceptedTerms,

        public bool $acceptedDataProcessing,
    ) {
    }
}
