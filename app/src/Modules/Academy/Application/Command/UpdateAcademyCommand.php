<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

use App\Modules\Academy\Application\Dto\UpdateAcademyInput;

final readonly class UpdateAcademyCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public UpdateAcademyInput $input,
    ) {
    }
}
