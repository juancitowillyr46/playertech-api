<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Command;

use App\Modules\Identity\Application\Dto\CreateUserInput;

final readonly class CreateUserCommand
{
    public function __construct(
        public string $actorId,
        public CreateUserInput $input,
        public ?string $academyId = null,
    ) {
    }
}
