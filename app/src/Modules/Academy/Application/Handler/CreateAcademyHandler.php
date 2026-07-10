<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\CreateAcademyCommand;
use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Shared\Domain\ValueObject\AuditTrail;
use App\Shared\Domain\ValueObject\Email;

final readonly class CreateAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(CreateAcademyCommand $command): AcademyResponse
    {
        if (null !== $this->academyRepository->findOneByContactEmail(new Email($command->input->contactEmail))) {
            throw new AcademyAlreadyExistsException();
        }

        $academy = Academy::create(
            AcademyId::generate(),
            new \App\Shared\Domain\ValueObject\Name($command->input->name),
            new \App\Shared\Domain\ValueObject\Email($command->input->contactEmail),
            null === $command->input->phone ? null : new \App\Shared\Domain\ValueObject\PhoneNumber($command->input->phone),
            $this->normalizeCountry($command->input->country),
            $command->input->department,
            null === $command->input->address ? null : new \App\Shared\Domain\ValueObject\Address($command->input->address),
            null === $command->input->city ? null : new \App\Shared\Domain\ValueObject\City($command->input->city),
            null,
            AuditTrail::create($command->actorId),
        );

        $this->academyRepository->save($academy);

        return AcademyResponse::fromAcademy($academy);
    }

    private function normalizeCountry(?string $country): string
    {
        $normalized = trim((string) $country);

        return '' === $normalized ? 'Colombia' : $normalized;
    }
}
