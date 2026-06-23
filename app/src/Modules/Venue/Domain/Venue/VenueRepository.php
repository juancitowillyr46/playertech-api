<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Venue;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface VenueRepository
{
    public function save(Venue $venue): void;

    public function findById(VenueId $venueId): ?Venue;

    /**
     * @return Venue[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue;
}