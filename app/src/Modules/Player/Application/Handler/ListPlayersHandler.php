<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Application\Response\PlayerListItemResponse;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListPlayersHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(ListPlayersQuery $query): PaginatedResult
    {
        $players = $this->playerRepository->findAllByAcademy(
            $query->academyId,
            $query->pagination,
        );

        $items = array_map(
            static fn ($player): PlayerListItemResponse => PlayerListItemResponse::fromPlayer($player),
            $players['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $players['total']);
    }
}
