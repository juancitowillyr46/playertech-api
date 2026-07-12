<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\ConfirmPasswordResetInput;

final readonly class ConfirmPasswordResetCommand
{
    public function __construct(
        public string $token,
        public ConfirmPasswordResetInput $input,
    ) {
    }
}
