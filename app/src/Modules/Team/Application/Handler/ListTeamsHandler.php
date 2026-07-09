<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Team\Application\Query\ListTeamsQuery;
use App\Modules\Team\Application\Response\TeamListItemResponse;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListTeamsHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
    ) {
    }

    /**
     * @return TeamListItemResponse[]
     */
    public function __invoke(ListTeamsQuery $query): PaginatedResult
    {
        $teams = $this->teamRepository->findAllByAcademy($query->academyId, $query->pagination);

        $items = array_map(
            static fn ($team) => TeamListItemResponse::fromTeam($team),
            $teams['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $teams['total']);
    }
}
