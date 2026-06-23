<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Command;

use App\Modules\Venue\Application\Dto\CreateVenueInput;

final readonly class CreateVenueCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public CreateVenueInput $input
    ) {
    }
}