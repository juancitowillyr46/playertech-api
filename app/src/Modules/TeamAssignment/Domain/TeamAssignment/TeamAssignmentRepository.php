<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Domain\TeamAssignment;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;

interface TeamAssignmentRepository
{
    public function save(TeamAssignment $assignment): void;

    public function findById(AcademyId $academyId, TeamAssignmentId $assignmentId): ?TeamAssignment;

    public function findActiveByPlayerAndTeam(AcademyId $academyId, PlayerId $playerId, \App\Modules\Team\Domain\Team\TeamId $teamId): ?TeamAssignment;

    /**
     * @return TeamAssignment[]
     */
    public function findAllByPlayer(AcademyId $academyId, PlayerId $playerId): array;

    public function findPrimaryByPlayer(AcademyId $academyId, PlayerId $playerId): ?TeamAssignment;

    public function findActiveByPlayerExcept(AcademyId $academyId, PlayerId $playerId, ?TeamAssignmentId $excludedAssignmentId = null): ?TeamAssignment;
}
