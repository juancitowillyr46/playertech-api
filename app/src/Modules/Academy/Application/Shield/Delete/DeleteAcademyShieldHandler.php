<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Shield\Delete;

use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Domain\ValueObject\Media;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class DeleteAcademyShieldHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(DeleteAcademyShieldCommand $command): AcademyResponse
    {
        $academy = $this->requireAcademy($command->academyId);
        $academy->updateShield(null, $command->actorId);
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
}
