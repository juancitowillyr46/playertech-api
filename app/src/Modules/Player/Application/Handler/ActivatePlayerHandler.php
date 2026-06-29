<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\ActivatePlayerCommand;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;

final readonly class ActivatePlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(ActivatePlayerCommand $command): void
    {
        $player = $this->playerFinder->findOrFail(
            new AcademyId($command->academyId),
            new PlayerId($command->playerId),
        );

        $player->activate($command->actorId);
        $this->playerRepository->save($player);
    }
}
