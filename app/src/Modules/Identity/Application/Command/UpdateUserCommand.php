<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\UpdateUserInput;

final readonly class UpdateUserCommand
{
    public function __construct(
        public string $actorId,
        public string $userId,
        public UpdateUserInput $input,
        public ?string $academyId = null,
    ) {
    }
}
