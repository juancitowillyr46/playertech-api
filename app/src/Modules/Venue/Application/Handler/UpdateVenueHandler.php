<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Command\UpdateVenueCommand;
use App\Modules\Venue\Application\Services\VenueFinder;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Notes;
use App\Shared\Domain\ValueObject\PhoneNumber;

final readonly class UpdateVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
        private VenueFinder $venueFinder,
    ) {
    }

    public function __invoke(UpdateVenueCommand $command): VenueResponse
    {
        $academyId = new AcademyId($command->academyId);
        $venueId = new VenueId($command->venueId);

        $venue = $this->venueFinder->findOrFail($academyId, $venueId);

        $venue->update(
            new Name($command->input->name),
            null === $command->input->address ? null : new Address($command->input->address),
            null === $command->input->city ? null : new City($command->input->city),
            null === $command->input->phone ? null : new PhoneNumber($command->input->phone),   
            null === $command->input->notes ? null : new Notes($command->input->notes),
            $command->actorId,
        );

        $this->venueRepository->save($venue);

        return VenueResponse::fromVenue($venue);
    }
}
