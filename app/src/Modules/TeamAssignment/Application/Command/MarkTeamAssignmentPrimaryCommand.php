<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Command;

final readonly class MarkTeamAssignmentPrimaryCommand
{
    public function __construct(public string $actorId, public string $academyId, public string $assignmentId)
    {
    }
}
