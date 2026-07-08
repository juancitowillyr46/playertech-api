<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Dto;

final readonly class CreateUserInput
{
    public function __construct(
        public ?string $fullName,

        public ?string $email,

        public ?string $password,

        public ?string $role,

        public ?string $academyId = null,
    ) {
    }
}
