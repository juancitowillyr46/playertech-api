<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\UpdateOwnNameInput;

final readonly class UpdateOwnNameCommand
{
    public function __construct(
        public string $actorId,
        public UpdateOwnNameInput $input,
    ) {
    }
}
