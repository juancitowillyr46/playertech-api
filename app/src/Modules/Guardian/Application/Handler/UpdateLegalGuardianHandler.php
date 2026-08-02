<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Command\UpdateLegalGuardianCommand;
use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\Exception\LegalGuardianAlreadyExistsException;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;

final readonly class UpdateLegalGuardianHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    public function __invoke(UpdateLegalGuardianCommand $command): LegalGuardianResponse
    {
        $academyId = new AcademyId($command->academyId->value());
        $guardianId = new LegalGuardianId($command->guardianId);
        $guardian = $this->guardianRepository->findById($academyId, $guardianId);

        if (null === $guardian) {
            throw new LegalGuardianNotFoundException();
        }

        $input = $command->input;
        $normalizedEmail = null;

        if (null !== $input->email) {
            $normalizedEmail = mb_strtolower(trim($input->email));
            $existing = $this->guardianRepository->findOneByEmail($academyId, $normalizedEmail);

            if (null !== $existing && !$existing->id()->equals($guardian->id())) {
                throw new LegalGuardianAlreadyExistsException();
            }
        }

        $guardian->update(
            $input->firstName ?? '',
            $input->lastName ?? '',
            $input->phone,
            $normalizedEmail,
            $input->documentType,
            $input->documentNumber,
            $input->address,
            $input->relationship ?? '',
            $command->actorId,
        );

        $this->guardianRepository->save($guardian);

        return LegalGuardianResponse::fromLegalGuardian($guardian);
    }
}
