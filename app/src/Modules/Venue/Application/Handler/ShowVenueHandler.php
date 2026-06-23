<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Query\ShowVenueQuery;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class ShowVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
    ) {
    }

    public function __invoke(ShowVenueQuery $query): VenueResponse
    {
        $venue = $this->requireVenue($query->venueId);

        return VenueResponse::fromVenue($venue);
    }

    private function requireVenue(string $venueId): Venue
    {
        if (!Uuid::isValid($venueId)) {
            throw new NotFoundHttpException('Identificador de sede inválido.');
        }

        $venueId = $this->venueRepository->findById(new VenueId($venueId));

        if (null === $venueId) {
            throw new NotFoundHttpException('Sede no encontrada.');
        }

        return $venueId;
    }
}
