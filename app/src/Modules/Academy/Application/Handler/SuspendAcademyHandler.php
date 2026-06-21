<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Command\SuspendAcademyCommand;
use App\Modules\Academy\Application\Response\AcademyView;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class SuspendAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(SuspendAcademyCommand $command): AcademyView
    {
        $academy = $this->requireAcademy($command->academyId);

        $academy->suspend($command->actorId);
        $this->academyRepository->save($academy);

        return AcademyView::fromAcademy($academy);
    }

    private function requireAcademy(string $academyId): Academy
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
