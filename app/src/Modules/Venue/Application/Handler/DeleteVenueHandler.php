<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Command\DeleteVenueCommand;
use App\Modules\Venue\Application\Services\VenueFinder;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;

final readonly class DeleteVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
        private VenueFinder $venueFinder
    ) {
    }

    public function __invoke(DeleteVenueCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);

        $venueId = new VenueId($command->venueId);

        $venue = $this->venueFinder->findOrFail($academyId, $venueId);

        $venue->delete($command->actorId);

        $this->venueRepository->save($venue);
    }
}
