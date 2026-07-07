<?php

declare(strict_types=1);

namespace App\Modules\Team\Domain\Team;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Domain\Category\CategoryId;
use App\Shared\Domain\ValueObject\Name;

interface TeamRepository
{
    public function save(Team $team): void;

    public function findById(AcademyId $academyId, TeamId $teamId): ?Team;

    public function findOneByAcademyCategoryAndName(
        AcademyId $academyId,
        CategoryId $categoryId,
        Name $name
    ): ?Team;

    /**
     * @return Team[]
     */
    public function findAllByAcademy(AcademyId $academyId): array;
}
