<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\ActivateUserInput;

final readonly class ActivateUserCommand
{
    public function __construct(
        public string $token,
        public ActivateUserInput $input,
    ) {
    }
}
