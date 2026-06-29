<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Player\Application\Command\CreatePlayerCommand;
use App\Modules\Player\Application\Response\PlayerResponse;
use App\Modules\Player\Domain\Exception\PlayerAlreadyExistsException;
use App\Modules\Player\Domain\Player\Player;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class CreatePlayerHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(CreatePlayerCommand $command): PlayerResponse
    {
        $academyId = new AcademyId($command->academyId);

        if (null !== $this->playerRepository->findOneByDocumentNumber($academyId, $command->input->documentNumber)) {
            throw new PlayerAlreadyExistsException();
        }

        $player = Player::create(
            PlayerId::generate(),
            $academyId,
            $command->input->firstName ?? '',
            $command->input->lastName ?? '',
            new \DateTimeImmutable($command->input->birthDate ?? ''),
            $command->input->documentNumber ?? '',
            null,
            AuditTrail::create($command->actorId),
        );

        $this->playerRepository->save($player);

        return PlayerResponse::fromPlayer($player);
    }
}
