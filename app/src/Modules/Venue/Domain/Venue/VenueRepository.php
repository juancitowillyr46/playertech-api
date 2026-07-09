<?php

declare(strict_types=1);

namespace App\Modules\Venue\Domain\Venue;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Application\Pagination\PaginationQuery;

interface VenueRepository
{
    public function save(Venue $venue): void;

    public function findById(AcademyId $academyId, VenueId $venueId): ?Venue;

    /**
     * @return array{items: Venue[], total: int}
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;

    public function findByAcademyAndId(AcademyId $academyId, VenueId $venueId): ?Venue;
}
