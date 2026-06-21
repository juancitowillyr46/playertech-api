<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\UpdateAcademyCommand;
use App\Modules\Academy\Application\Dto\AcademyProfileData;
use App\Modules\Academy\Application\Response\AcademyView;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(UpdateAcademyCommand $command): AcademyView
    {
        $academy = $this->requireAcademy($command->academyId);
        $profile = AcademyProfileData::fromArray($command->payload);

        $duplicate = $this->academyRepository->findOneByContactEmail($profile->contactEmail());
        if (null !== $duplicate && $duplicate->id()->value() !== $academy->id()->value()) {
            throw new ConflictHttpException('El correo de contacto ya existe.');
        }

        $academy->updateProfile(
            $profile->name(),
            $profile->contactEmail(),
            $profile->phone(),
            $profile->address(),
            $profile->city(),
            $profile->logo(),
            $command->actorId,
        );

        $this->academyRepository->save($academy);

        return AcademyView::fromAcademy($academy);
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
}
