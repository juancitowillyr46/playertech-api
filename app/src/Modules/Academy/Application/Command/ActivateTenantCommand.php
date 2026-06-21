<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

final readonly class ActivateTenantCommand
{
    public function __construct(
        public string $token,
    ) {
    }
}
