<?php

declare(strict_types=1);

namespace App\Modules\Player\Application\Guardian\RemoveAssociation;

use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Modules\Player\Domain\Exception\PlayerGuardianNotFoundException;
use App\Modules\Player\Domain\Player\PlayerRepository;
use App\Modules\Player\Domain\PlayerGuardian\PlayerGuardianRepository;

final readonly class RemoveGuardianAssociationHandler
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private LegalGuardianRepository $guardianRepository,
        private PlayerGuardianRepository $playerGuardianRepository,
    ) {
    }

    public function __invoke(RemoveGuardianAssociationCommand $command): void
    {
        if (null === $this->playerRepository->findById($command->academyId, $command->playerId)) {
            throw new \App\Modules\Player\Domain\Exception\PlayerNotFoundException();
        }

        if (null === $this->guardianRepository->findById($command->academyId, $command->guardianId)) {
            throw new LegalGuardianNotFoundException();
        }

        $playerGuardian = $this->playerGuardianRepository->findByPlayerAndGuardian($command->academyId, $command->playerId, $command->guardianId);

        if (null === $playerGuardian) {
            throw new PlayerGuardianNotFoundException();
        }

        $wasPrimary = $playerGuardian->isPrimary();

        $this->playerGuardianRepository->remove($playerGuardian);

        if (!$wasPrimary) {
            return;
        }

        $remainingGuardians = $this->playerGuardianRepository->findAllByPlayer($command->academyId, $command->playerId);
        $candidate = null;

        foreach (array_reverse($remainingGuardians) as $relation) {
            if (!$relation->isPrimary()) {
                $candidate = $relation;
                break;
            }
        }

        if (null === $candidate) {
            return;
        }

        $candidate->promote($command->actorId);
        $this->playerGuardianRepository->save($candidate);
    }
}
