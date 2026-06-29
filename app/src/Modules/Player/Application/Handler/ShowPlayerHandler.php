<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Player\Application\Query\ShowPlayerQuery;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;

final readonly class ShowPlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
    ) {
    }

    public function __invoke(ShowPlayerQuery $query): PlayerResponse
    {
        $player = $this->playerFinder->findOrFail($query->academyId, $query->playerId);

        return PlayerResponse::fromPlayer($player);
    }
}
