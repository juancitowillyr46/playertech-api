<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Command;

use App\Modules\Venue\Application\Dto\UpdateVenueInput;

final readonly class DeleteVenueCommand
{
    public function __construct(
        public string $actorId,
        public string $venueId,
    ) {
    }
}
