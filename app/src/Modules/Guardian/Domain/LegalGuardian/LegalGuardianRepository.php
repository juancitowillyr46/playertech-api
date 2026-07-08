<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Domain\LegalGuardian;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface LegalGuardianRepository
{
    public function save(LegalGuardian $guardian): void;

    public function findById(AcademyId $academyId, LegalGuardianId $guardianId): ?LegalGuardian;

    public function findOneByEmail(AcademyId $academyId, string $email): ?LegalGuardian;

    /**
     * @return LegalGuardian[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;
}
