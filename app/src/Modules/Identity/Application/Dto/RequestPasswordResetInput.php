<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Dto;

final readonly class RequestPasswordResetInput
{
    public function __construct(
        public ?string $email,
    ) {
    }
}
