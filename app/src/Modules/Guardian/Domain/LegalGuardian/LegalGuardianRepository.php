<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\LegalGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Shared\Application\Pagination\PaginationQuery;

interface LegalGuardianRepository
{
    public function save(LegalGuardian $guardian): void;

    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian;

    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian;

    /**
     * @return array{items: LegalGuardian[], total: int}
     */
    public function findAllByAcademy(AcademyId $academyId, PaginationQuery $pagination): array;
}
