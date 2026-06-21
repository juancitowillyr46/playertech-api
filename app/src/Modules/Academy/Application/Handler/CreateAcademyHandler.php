<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\CreateAcademyCommand;
use App\Modules\Academy\Application\Dto\AcademyProfileData;
use App\Modules\Academy\Application\Response\AcademyView;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Modules\Academy\Domain\Exception\AcademyAlreadyExistsException;
use App\Shared\Domain\ValueObject\AuditTrail;

final readonly class CreateAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(CreateAcademyCommand $command): AcademyView
    {
        $profile = AcademyProfileData::fromArray($command->payload);

        if (null !== $this->academyRepository->findOneByContactEmail($profile->contactEmail())) {
            throw new AcademyAlreadyExistsException();
        }

        $academy = Academy::create(
            AcademyId::generate(),
            $profile->name(),
            $profile->contactEmail(),
            $profile->phone(),
            $profile->address(),
            $profile->city(),
            $profile->logo(),
            AuditTrail::create($command->actorId),
        );

        $this->academyRepository->save($academy);

        return AcademyView::fromAcademy($academy);
    }
}
