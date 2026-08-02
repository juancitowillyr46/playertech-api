<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Player\Application\Query\ShowPlayerSummaryQuery;
use App\Modules\Player\Application\Response\PlayerSummaryResponse;
use App\Modules\Player\Application\Services\PlayerFinder;

final readonly class ShowPlayerSummaryHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
    ) {
    }

    public function __invoke(ShowPlayerSummaryQuery $query): PlayerSummaryResponse
    {
        $player = $this->playerFinder->findOrFail($query->academyId, $query->playerId);

        return PlayerSummaryResponse::fromPlayer($player);
    }
}
