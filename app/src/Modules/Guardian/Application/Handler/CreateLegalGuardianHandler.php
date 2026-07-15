<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Guardian\Application\Command\CreateLegalGuardianCommand;
use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\Exception\LegalGuardianAlreadyExistsException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardian;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianId;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class CreateLegalGuardianHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    public function __invoke(CreateLegalGuardianCommand $command): LegalGuardianResponse
    {
        $input = $command->input;

        if (null !== $input->email && null !== $this->guardianRepository->findOneByEmail($command->academyId, $input->email)) {
            throw new LegalGuardianAlreadyExistsException();
        }

        $guardian = LegalGuardian::create(
            LegalGuardianId::generate(),
            $command->academyId,
            $input->firstName ?? '',
            $input->lastName ?? '',
            $input->phone,
            $input->email,
            $input->documentType,
            $input->documentNumber,
            $input->address,
            $input->relationship ?? '',
            AuditTrail::create($command->actorId),
        );

        $this->guardianRepository->save($guardian);

        return new LegalGuardianResponse(
            $guardian->id()->value(),
            $guardian->academyId()->value(),
            $guardian->firstName(),
            $guardian->lastName(),
            $guardian->phone(),
            $guardian->email(),
            $guardian->documentType(),
            $guardian->documentNumber(),
            $guardian->address(),
            $guardian->relationship(),
            $guardian->status()->value(),
        );
    }
}
