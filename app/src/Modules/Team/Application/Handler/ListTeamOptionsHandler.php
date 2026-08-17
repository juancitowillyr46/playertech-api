<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Team\Application\Query\ListTeamOptionsQuery;
use App\Modules\Team\Application\Response\TeamOptionResponse;
use App\Modules\Team\Domain\Team\TeamRepository;

final readonly class ListTeamOptionsHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private CategoryFinder $categoryFinder,
    ) {
    }

    /**
     * @return TeamOptionResponse[]
     */
    public function __invoke(ListTeamOptionsQuery $query): array
    {
        $teams = $this->teamRepository->findActiveByAcademyWithSearch(
            $query->academyId,
            $query->query,
        );

        return array_values(array_map(function ($team) use ($query): TeamOptionResponse {
            $category = $this->categoryFinder->findOrFail($query->academyId, $team->categoryId());

            return TeamOptionResponse::fromTeam($team, $category->name()->value());
        }, $teams));
    }
}
