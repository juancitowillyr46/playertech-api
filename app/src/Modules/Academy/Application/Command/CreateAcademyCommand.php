<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

use App\Modules\Academy\Application\Dto\CreateAcademyInput;

final readonly class CreateAcademyCommand
{
    public function __construct(
        public string $actorId,
        public CreateAcademyInput $input,
    ) {
    }
}
