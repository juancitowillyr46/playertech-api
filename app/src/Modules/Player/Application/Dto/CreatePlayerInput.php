<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Dto;

final readonly class CreatePlayerInput
{
    public function __construct(
        public ?string $firstName,

        public ?string $lastName,

        public ?string $birthDate,

        public ?string $documentNumber,
    ) {
    }
}
