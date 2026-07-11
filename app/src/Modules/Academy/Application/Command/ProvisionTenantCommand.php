<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

use App\Modules\Academy\Application\Dto\ProvisionTenantInput;

final readonly class ProvisionTenantCommand
{
    public function __construct(
        public string $actorId,
        public ProvisionTenantInput $input,
    ) {
    }
}
