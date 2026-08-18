<?php

declare(strict_types=1);

namespace App\Modules\TeamAssignment\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Team\Application\Response\TeamOptionResponse;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Modules\TeamAssignment\Application\Query\ListAvailableTeamsForPlayerQuery;
use App\Modules\TeamAssignment\Domain\TeamAssignment\TeamAssignmentRepository;

final readonly class ListAvailableTeamsForPlayerHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private TeamAssignmentRepository $assignmentRepository,
        private CategoryFinder $categoryFinder,
    ) {
    }

    /**
     * @return TeamOptionResponse[]
     */
    public function __invoke(ListAvailableTeamsForPlayerQuery $query): array
    {
        $academyId = new AcademyId($query->academyId);
        $playerId = new PlayerId($query->playerId);
        $teams = $this->teamRepository->findActiveByAcademyWithSearch($academyId, $query->query);
        $assignedTeamIds = [];

        foreach ($this->assignmentRepository->findAllByPlayer($academyId, $playerId) as $assignment) {
            if ($assignment->isActive() && null === $assignment->deletedAt()) {
                $assignedTeamIds[$assignment->teamId()->value()] = true;
            }
        }

        $filteredTeams = array_values(array_filter(
            $teams,
            static fn ($team): bool => !isset($assignedTeamIds[$team->id()->value()])
        ));

        return array_values(array_map(function ($team) use ($academyId): TeamOptionResponse {
            $category = $this->categoryFinder->findOrFail($academyId, $team->categoryId());

            return TeamOptionResponse::fromTeam($team, $category->name()->value());
        }, $filteredTeams));
    }
}
