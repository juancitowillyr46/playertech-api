<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\ChangePrimary;

use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerGuardianNotFoundException;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;

final readonly class ChangePrimaryGuardianHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
    ) {
    }

    public function __invoke(ChangePrimaryGuardianCommand $command): ChangePrimaryGuardianResponse
    {
        if (null === $this->playerRepository->findById($command->academyId, $command->playerId)) {
            throw new \App\Modules\Player\Domain\Exception\PlayerNotFoundException();
        }

        if (null === $this->guardianRepository->findById($command->academyId, $command->guardianId)) {
            throw new LegalGuardianNotFoundException();
        }

        $target = $this->playerGuardianRepository->findByPlayerAndGuardian($command->academyId, $command->playerId, $command->guardianId);

        if (null === $target) {
            throw new PlayerGuardianNotFoundException();
        }

        foreach ($this->playerGuardianRepository->findAllByPlayer($command->academyId, $command->playerId) as $playerGuardian) {
            if ($playerGuardian->guardianId()->equals($command->guardianId)) {
                $playerGuardian->promote($command->actorId);
            } else {
                $playerGuardian->demote($command->actorId);
            }

            $this->playerGuardianRepository->save($playerGuardian);
        }

        return new ChangePrimaryGuardianResponse(
            $target->id()->value(),
            $target->academyId()->value(),
            $target->playerId()->value(),
            $target->guardianId()->value(),
            true,
        );
    }
}
