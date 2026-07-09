<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Query\ListVenuesQuery;
use App\Modules\Venue\Application\Response\VenueListItemResponse;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListVenuesHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
    ) {
    }

    /**
     * @return VenueListItemResponse[]
     */
    public function __invoke(ListVenuesQuery $query): PaginatedResult
    {
        $venues = $this->venueRepository->findAllByAcademy(
            $query->academyId
            ,
            $query->pagination
        );

        $items = array_map(
            static fn ($venue) => VenueListItemResponse::fromVenue($venue),
            $venues['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $venues['total']);
    }
}
