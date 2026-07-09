<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\TeamAssignment\Application\Query\ShowPlayerTeamAssignmentsQuery;
use App\Modules\TeamAssignment\Application\Response\TeamAssignmentResponse;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class ShowPlayerTeamAssignmentsHandler
{
    public function __construct(private TeamAssignmentRepository $assignmentRepository)
    {
    }

    /**
     * @return TeamAssignmentResponse[]
     */
    public function __invoke(ShowPlayerTeamAssignmentsQuery $query): array
    {
        $assignments = $this->assignmentRepository->findAllByPlayer(new AcademyId($query->academyId), new PlayerId($query->playerId));

        return array_map(static fn ($assignment) => TeamAssignmentResponse::fromEntity($assignment), $assignments);
    }
}
