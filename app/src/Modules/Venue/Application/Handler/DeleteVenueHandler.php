<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Command\DeleteVenueCommand;
use App\Modules\Venue\Application\Command\UpdateVenueCommand;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Notes;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class DeleteVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
    ) {
    }

    public function __invoke(DeleteVenueCommand $command): VenueResponse
    {
        $venue = $this->requireVenue($command->venueId);

        $venue->delete($command->actorId);

        $this->venueRepository->save($venue);

        return VenueResponse::fromVenue($venue);
    }

    private function requireVenue(string $venueId): \App\Modules\Venue\Domain\Venue\Venue
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
