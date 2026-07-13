<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\RequestPasswordResetInput;

final readonly class RequestPasswordResetCommand
{
    public function __construct(
        public RequestPasswordResetInput $input,
        public string $publicUrl,
    ) {
    }
}
