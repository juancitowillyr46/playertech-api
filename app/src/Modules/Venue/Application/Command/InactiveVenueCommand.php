<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Command;

final readonly class InactiveVenueCommand
{
    public function __construct(
        public string $actorId,
        public string $venueId,
    ) {
    }
}
