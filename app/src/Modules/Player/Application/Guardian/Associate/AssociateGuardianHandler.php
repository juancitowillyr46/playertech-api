<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\Associate;

use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerGuardianAlreadyExistsException;
use App\Modules\Player\Domain\Player\PlayerId;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardian;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianId;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class AssociateGuardianHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
    ) {
    }

    public function __invoke(AssociateGuardianCommand $command): AssociateGuardianResponse
    {
        $guardianId = new LegalGuardianId($command->input->guardianId ?? '');

        if (null === $this->playerRepository->findById($command->academyId, $command->playerId)) {
            throw new \App\Modules\Player\Domain\Exception\PlayerNotFoundException();
        }

        if (null === $this->guardianRepository->findById($command->academyId, $guardianId)) {
            throw new LegalGuardianNotFoundException();
        }

        if (null !== $this->playerGuardianRepository->findByPlayerAndGuardian($command->academyId, $command->playerId, $guardianId)) {
            throw new PlayerGuardianAlreadyExistsException();
        }

        $isPrimary = $command->input->isPrimary ?? (0 === count($this->playerGuardianRepository->findAllByPlayer($command->academyId, $command->playerId)));

        if ($isPrimary) {
            $currentPrimary = $this->playerGuardianRepository->findPrimaryByPlayer($command->academyId, $command->playerId);

            if (null !== $currentPrimary) {
                $currentPrimary->demote($command->actorId);
                $this->playerGuardianRepository->save($currentPrimary);
            }
        }

        $playerGuardian = PlayerGuardian::create(
            PlayerGuardianId::generate(),
            $command->academyId,
            $command->playerId,
            $guardianId,
            $isPrimary,
            AuditTrail::create($command->actorId),
        );

        $this->playerGuardianRepository->save($playerGuardian);

        return new AssociateGuardianResponse(
            $playerGuardian->id()->value(),
            $playerGuardian->academyId()->value(),
            $playerGuardian->playerId()->value(),
            $playerGuardian->guardianId()->value(),
            $playerGuardian->isPrimary(),
        );
    }
}
