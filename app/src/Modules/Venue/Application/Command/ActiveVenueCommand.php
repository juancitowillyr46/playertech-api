<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Command;

final readonly class ActiveVenueCommand
{
    public function __construct(
        public string $actorId,
        public string $academyId,
        public string $venueId,
    ) {
    }
}
