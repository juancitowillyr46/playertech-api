<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\InactivatePlayerCommand;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;

final readonly class InactivatePlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(InactivatePlayerCommand $command): void
    {
        $player = $this->playerFinder->findOrFail(
            new AcademyId($command->academyId),
            new PlayerId($command->playerId),
        );

        $player->inactivate($command->actorId);
        $this->playerRepository->save($player);
    }
}
