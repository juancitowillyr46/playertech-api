<?php

declare(strict_types=1);

namespace App\Modules\Academy\Domain\Academy;

use App\Shared\Domain\ValueObject\Email;

interface AcademyRepository
{
    public function save(Academy $academy): void;

    public function findById(AcademyId $academyId): ?Academy;

    /**
     * @return Academy[]
     */
    public function findAllOrdered(): array;

    public function findOneByContactEmail(Email $contactEmail): ?Academy;
}
