<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Team\Application\Query\ShowTeamQuery;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Application\Services\TeamFinder;
use App\Modules\Category\Application\Services\CategoryFinder;

final readonly class ShowTeamHandler
{
    public function __construct(
        private TeamFinder $teamFinder,
        private CategoryFinder $categoryFinder,
    ) {
    }

    public function __invoke(ShowTeamQuery $query): TeamResponse
    {
        $team = $this->teamFinder->findOrFail(
            $query->academyId,
            $query->teamId,
        );

        $category = $this->categoryFinder->findOrFail($query->academyId, $team->categoryId());

        return TeamResponse::fromTeam($team, $category->name()->value());
    }
}
