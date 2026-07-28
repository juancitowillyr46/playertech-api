<?php

declare(strict_types=1);

namespace App\Modules\Player\Domain\Import;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface PlayerImportJobRepository
{
    public function save(PlayerImportJob $job): void;

    public function findById(AcademyId $academyId, PlayerImportJobId $jobId): ?PlayerImportJob;
}
