<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Query;

final readonly class ShowVenueQuery
{
    public function __construct(
        public string $venueId,
    ) {
    }
}
