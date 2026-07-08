<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Dto;

final readonly class UpdateUserInput
{
    public function __construct(
        public ?string $fullName,

        public ?string $email,

        public ?string $role,
    ) {
    }
}
