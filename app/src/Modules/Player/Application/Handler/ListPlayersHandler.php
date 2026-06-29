<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Player\Application\Query\ListPlayersQuery;
use App\Modules\Player\Application\Response\PlayerListItemResponse;
use App\Modules\Player\Domain\Player\PlayerRepository;

final readonly class ListPlayersHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    /**
     * @return PlayerListItemResponse[]
     */
    public function __invoke(ListPlayersQuery $query): array
    {
        $players = $this->playerRepository->findAllByAcademy($query->academyId);

        return array_map(
            static fn ($player): PlayerListItemResponse => PlayerListItemResponse::fromPlayer($player),
            $players
        );
    }
}
