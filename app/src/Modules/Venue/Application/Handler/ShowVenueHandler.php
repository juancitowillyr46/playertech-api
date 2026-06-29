<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Query\ShowVenueQuery;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Application\Services\VenueFinder;

final readonly class ShowVenueHandler
{
    public function __construct(
        private VenueFinder $venueFinder,
    ) {
    }

    public function __invoke(ShowVenueQuery $query): VenueResponse
    {
        $venue = $this->venueFinder->findOrFail($query->academyId, $query->venueId);

        return VenueResponse::fromVenue($venue);
    }
}
