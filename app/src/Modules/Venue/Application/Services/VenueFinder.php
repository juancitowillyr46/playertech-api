<?php

declare(strict_types=1);

namespace App\Modules\Venue\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Venue\Domain\Exception\VenueNotFoundException;
use App\Modules\Venue\Domain\Venue\Venue;
use App\Modules\Venue\Domain\Venue\VenueId;
use App\Modules\Venue\Domain\Venue\VenueRepository;
use App\Shared\Domain\Exception\IdInvalidException;
use Symfony\Component\Uid\Uuid;

final readonly class VenueFinder
{
    public function __construct(
        private VenueRepository $venueRepository
    ) {
    }

    public function findOrFail(
        AcademyId $academyId,
        VenueId $venueId
    ): Venue {

        if (!Uuid::isValid($academyId->value()) || !Uuid::isValid($venueId->value())) {
            throw new IdInvalidException();
        }

        $venue = $this->venueRepository->findById(
            $academyId,
            $venueId
        );

        if ($venue === null) {
            throw new VenueNotFoundException();
        }

        return $venue;
    }
}
