<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\UpdatePlayerCommand;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Application\Services\PlayerFinder;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;

final readonly class UpdatePlayerHandler
{
    public function __construct(
        private PlayerFinder $playerFinder,
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(UpdatePlayerCommand $command): PlayerResponse
    {
        $academyId = new AcademyId($command->academyId);
        $playerId = new PlayerId($command->playerId);
        $player = $this->playerFinder->findOrFail($academyId, $playerId);

        $duplicate = $this->playerRepository->findOneByDocumentNumber($academyId, $command->input->documentNumber());
        if (null !== $duplicate && $duplicate->id()->value() !== $player->id()->value()) {
            throw new PlayerAlreadyExistsException();
        }

        $player->updateProfile(
            $command->input->firstName(),
            $command->input->lastName(),
            new \DateTimeImmutable($command->input->birthDate()),
            $command->input->documentNumber(),
            $command->actorId,
        );

        $this->playerRepository->save($player);

        return PlayerResponse::fromPlayer($player);
    }
}
