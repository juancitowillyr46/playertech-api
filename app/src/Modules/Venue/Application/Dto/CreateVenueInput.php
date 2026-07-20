<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Dto;

final readonly class CreateVenueInput
{
    public function __construct(
        public string $name,

        public ?string $address = null,

        public ?string $city = null,

        public ?string $country = null,

        public ?string $department = null,

        public ?string $phone = null,

        public ?string $notes = null,

        public bool $isPrimary = false,
    ) {}
}
