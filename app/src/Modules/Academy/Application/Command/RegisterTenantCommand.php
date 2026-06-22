<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

use App\Modules\Academy\Application\Dto\TenantSignupInput;

final readonly class RegisterTenantCommand
{
    public function __construct(
        public TenantSignupInput $input,
    ) {
    }
}
