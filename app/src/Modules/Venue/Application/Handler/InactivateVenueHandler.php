<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Command\InactiveVenueCommand;
use App\Modules\Venue\Application\Command\ReactivateAcademyCommand;
use App\Modules\Venue\Application\Response\AcademyResponse;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\AcademyId;
use App\Modules\Venue\Domain\Venue\AcademyRepository;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Infrastructure\Persistence\VenueRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class InactivateVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
    ) {
    }

    public function __invoke(InactiveVenueCommand $command): VenueResponse
    {
        $venue = $this->requireVenue($command->venueId);

        $venue->inactivate($command->actorId);
        $this->venueRepository->save($venue);

        return VenueResponse::fromVenue($venue);
    }

    private function requireVenue(string $venueId): Venue
    {
        if (!Uuid::isValid($venueId)) {
            throw new NotFoundHttpException('Identificador de sede inválido.');
        }

        $venue = $this->venueRepository->findById(new VenueId($venueId));

        if (null === $venue) {
            throw new NotFoundHttpException('Sede no encontrada.');
        }

        return $venue;
    }
}
