<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Venue\Application\Command\CreateVenueCommand;
use App\Modules\Venue\Application\Response\VenueResponse;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Shared\Domain\ValueObject\Notes;

final readonly class CreateVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository
    ) {
    }

    public function __invoke(CreateVenueCommand $command): VenueResponse
    {
        $venue = Venue::create(
            VenueId::generate(),
            new AcademyId($command->academyId),
            new Name($command->input->name),
            $command->input->address ? new Address($command->input->address) : null,
            $command->input->city ? new City($command->input->city) : null,
            $command->input->country,
            $command->input->department,
            $command->input->phone ? new PhoneNumber($command->input->phone) : null,
            $command->input->notes ? new Notes($command->input->notes) : null,
            $command->input->isPrimary,
            AuditTrail::create($command->actorId)
        );

        $this->venueRepository->save($venue);

        return VenueResponse::fromVenue($venue);
    }
}
