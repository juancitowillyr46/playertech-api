<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Dto;

final readonly class ProvisionTenantInput
{
    public function __construct(
        public ?string $name,

        public ?string $contactEmail,

        public ?string $phone = null,

        public ?string $country = null,

        public ?string $department = null,

        public ?string $address = null,

        public ?string $city = null,

        public ?string $adminName = null,

        public ?string $adminEmail = null,

        public ?string $onboardingCategoryId = null,

        public ?string $teamName = null,
    ) {
    }
}
