<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\TeamAssignment\Application\Query\ShowPlayerTeamAssignmentsQuery;
use App\Modules\TeamAssignment\Application\Response\PlayerTeamAssignmentItemResponse;
use App\Modules\TeamAssignment\Application\Services\PlayerTeamAssignmentTransformer;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class ShowPlayerTeamAssignmentsHandler
{
    public function __construct(
        private TeamAssignmentRepository $assignmentRepository,
        private PlayerTeamAssignmentTransformer $transformer,
    )
    {
    }

    /**
     * @return PlayerTeamAssignmentItemResponse[]
     */
    public function __invoke(ShowPlayerTeamAssignmentsQuery $query): array
    {
        $assignments = $this->assignmentRepository->findAllByPlayer(new AcademyId($query->academyId), new PlayerId($query->playerId));

        return array_map(fn ($assignment) => $this->transformer->transform($assignment), $assignments);
    }
}
