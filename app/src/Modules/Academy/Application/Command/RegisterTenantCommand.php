<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

final readonly class RegisterTenantCommand
{
    public function __construct(
        public array $payload,
    ) {
    }
}
