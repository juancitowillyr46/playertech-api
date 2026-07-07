<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Team;

use App\Modules\Academy\Domain\Academy\AcademyId;

interface TeamRepository
{
    public function save(Team $team): void;

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team;

    /**
     * @return Team[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;
}
