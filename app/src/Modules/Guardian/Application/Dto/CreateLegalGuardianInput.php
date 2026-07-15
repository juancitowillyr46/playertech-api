<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Dto;

final readonly class CreateLegalGuardianInput
{
    public function __construct(
        public ?string $firstName,

        public ?string $lastName,

        public ?string $phone = null,

        public ?string $email = null,

        public ?string $documentType = null,

        public ?string $documentNumber = null,

        public ?string $address = null,

        public ?string $relationship = null,
    ) {
    }
}
