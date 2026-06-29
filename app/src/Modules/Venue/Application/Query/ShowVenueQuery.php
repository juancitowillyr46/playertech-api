<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Query;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Venue\VenueId;

final readonly class ShowVenueQuery
{
    public function __construct(
        public AcademyId $academyId,
        public VenueId $venueId,
    ) {
    }
}
