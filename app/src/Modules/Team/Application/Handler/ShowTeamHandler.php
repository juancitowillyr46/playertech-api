<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Team\Application\Query\ShowTeamQuery;
use App\Modules\Team\Application\Response\TeamResponse;
use App\Modules\Team\Application\Services\TeamFinder;

final readonly class ShowTeamHandler
{
    public function __construct(
        private TeamFinder $teamFinder,
    ) {
    }

    public function __invoke(ShowTeamQuery $query): TeamResponse
    {
        $team = $this->teamFinder->findOrFail(
            $query->academyId,
            $query->teamId,
        );

        return TeamResponse::fromTeam($team);
    }
}
