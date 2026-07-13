<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Dto;

final readonly class ConfirmPasswordResetInput
{
    public function __construct(
        public ?string $password,
        public ?string $passwordConfirmation,
    ) {
    }
}
