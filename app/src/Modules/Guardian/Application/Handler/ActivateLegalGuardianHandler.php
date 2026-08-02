<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\ActivateLegalGuardianCommand;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;

final readonly class ActivateLegalGuardianHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    public function __invoke(ActivateLegalGuardianCommand $command): void
    {
        $academyId = new AcademyId($command->academyId->value());
        $guardianId = new LegalGuardianId($command->guardianId);
        $guardian = $this->guardianRepository->findById($academyId, $guardianId);

        if (null === $guardian) {
            throw new LegalGuardianNotFoundException();
        }

        $guardian->activate($command->actorId);
        $this->guardianRepository->save($guardian);
    }
}
