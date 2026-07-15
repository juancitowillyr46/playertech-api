<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Command;

use App\Modules\Academy\Application\Dto\UpdateAcademyTaxProfileInput;

final readonly class UpdateAcademyTaxProfileCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public UpdateAcademyTaxProfileInput $input,
    ) {
    }
}
