<?php

declare(strict_types=1);

namespace App\Modules\Team\Application\Handler;

use App\Modules\Category\Application\Services\CategoryFinder;
use App\Modules\Team\Application\Query\ListTeamsQuery;
use App\Modules\Team\Application\Response\TeamListItemResponse;
use App\Modules\Team\Domain\Team\TeamRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListTeamsHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private CategoryFinder $categoryFinder,
    ) {
    }

    /**
     * @return TeamListItemResponse[]
     */
    public function __invoke(ListTeamsQuery $query): PaginatedResult
    {
        $teams = $this->teamRepository->findAllByAcademy($query->academyId, $query->pagination);

        $items = array_map(
            function ($team) use ($query): TeamListItemResponse {
                $category = $this->categoryFinder->findOrFail($query->academyId, $team->categoryId());

                return TeamListItemResponse::fromTeam($team, $category->name()->value());
            },
            $teams['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $teams['total']);
    }
}
