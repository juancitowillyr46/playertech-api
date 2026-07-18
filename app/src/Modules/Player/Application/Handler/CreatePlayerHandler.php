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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class CreatePlayerHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(CreatePlayerCommand $command): PlayerResponse
    {
        $academyId = new AcademyId($command->academyId);
        $input = $command->input;

        if (null === $input->documentType || null === $input->firstName || null === $input->lastName || null === $input->birthDate || null === $input->documentNumber) {
            throw new BadRequestHttpException('Missing required player input.');
        }

        if (null !== $this->playerRepository->findOneByDocumentNumber($academyId, $input->documentNumber)) {
            throw new PlayerAlreadyExistsException();
        }

        $player = Player::create(
            PlayerId::generate(),
            $academyId,
            $input->documentType,
            $input->firstName,
            $input->lastName,
            new \DateTimeImmutable($input->birthDate),
            $input->documentNumber,
            $input->email,
            $input->phone,
            $input->nationality,
            $input->gender,
            $input->federationId,
            $input->dominantFoot,
            null,
            null,
            AuditTrail::create($command->actorId),
        );

        $this->playerRepository->save($player);

        return PlayerResponse::fromPlayer($player);
    }
}
