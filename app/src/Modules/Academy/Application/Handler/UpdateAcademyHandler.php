<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Modules\Academy\Domain\Exception\AcademyPhoneAlreadyExistsException;
use App\Shared\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\City;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\PhoneNumber;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(UpdateAcademyCommand $command): AcademyResponse
    {
        $academy = $this->requireAcademy($command->academyId);
        $phone = null === $command->input->phone ? null : new PhoneNumber($command->input->phone);

        $duplicate = $this->academyRepository->findOneByContactEmail(new Email($command->input->contactEmail));
        if (null !== $duplicate && $duplicate->id()->value() !== $academy->id()->value()) {
            throw new AcademyAlreadyExistsException();
        }

        if (null !== $phone) {
            $duplicatePhone = $this->academyRepository->findOneByPhone($phone);
            if (null !== $duplicatePhone && $duplicatePhone->id()->value() !== $academy->id()->value()) {
                throw new AcademyPhoneAlreadyExistsException();
            }
        }

        $academy->updateProfile(
            new Name($command->input->name),
            new Email($command->input->contactEmail),
            $phone,
            $this->normalizeCountry($command->input->country),
            $command->input->department,
            $command->input->taxIdType,
            $command->input->taxIdNumber,
            $command->input->taxCheckDigit,
            $command->input->taxRegime,
            $command->input->billingEmail,
            null === $command->input->address ? null : new Address($command->input->address),
            null === $command->input->city ? null : new City($command->input->city),
            $command->actorId,
        );

        $this->academyRepository->save($academy);

        return AcademyResponse::fromAcademy($academy);
    }

    private function requireAcademy(string $academyId): \App\Modules\Academy\Domain\Academy\Academy
    {
        if (!Uuid::isValid($academyId)) {
            throw new NotFoundHttpException('Identificador de academia inválido.');
        }

        $academy = $this->academyRepository->findById(new AcademyId($academyId));

        if (null === $academy) {
            throw new NotFoundHttpException('Academia no encontrada.');
        }

        return $academy;
    }

    private function normalizeCountry(?string $country): string
    {
        $normalized = trim((string) $country);

        return '' === $normalized ? 'Colombia' : $normalized;
    }
}
