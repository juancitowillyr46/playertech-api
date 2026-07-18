<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Dto;

final readonly class UpdatePlayerInput
{
    public function __construct(
        public ?string $documentType,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $birthDate,
        public ?string $documentNumber,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $nationality = null,
        public ?string $gender = null,
        public ?string $federationId = null,
        public ?string $dominantFoot = null,
    ) {
    }
}
