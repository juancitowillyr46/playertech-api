<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Services;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Domain\Exception\PlayerNotFoundException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\Exception\IdInvalidException;
use Symfony\Component\Uid\Uuid;

final readonly class PlayerFinder
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function findOrFail(AcademyId $academyId, PlayerId $playerId): Player
    {
        if (!Uuid::isValid($academyId->value()) || !Uuid::isValid($playerId->value())) {
            throw new IdInvalidException();
        }

        $player = $this->playerRepository->findById($academyId, $playerId);

        if (null === $player) {
            throw new PlayerNotFoundException();
        }

        return $player;
    }
}
