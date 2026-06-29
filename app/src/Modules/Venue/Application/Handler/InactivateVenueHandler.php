<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Application\Command\InactiveVenueCommand;
use App\Modules\Venue\Application\Services\VenueFinder;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;

final readonly class InactivateVenueHandler
{
    public function __construct(
        private VenueRepository $venueRepository,
        private VenueFinder $venueFinder
    ) {
    }

    public function __invoke(InactiveVenueCommand $command): void
    {
        $academyId = new AcademyId($command->academyId);

        $venueId = new VenueId($command->venueId);

        $venue = $this->venueFinder->findOrFail($academyId, $venueId);

        $venue->inactivate($command->actorId);

        $this->venueRepository->save($venue);
    }
}
