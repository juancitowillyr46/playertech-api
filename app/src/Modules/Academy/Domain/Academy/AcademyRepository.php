<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\PhoneNumber;
use App\Shared\Application\Pagination\PaginationQuery;

interface AcademyRepository
{
    public function save(Academy $academy): void;

    public function findById(AcademyId $academyId): ?Academy;

    /**
     * @return array{items: Academy[], total: int}
     */
    public function findAllOrdered(PaginationQuery $pagination): array;

    public function findOneByContactEmail(Email $contactEmail): ?Academy;

    public function findOneByPhone(PhoneNumber $phone): ?Academy;
}
