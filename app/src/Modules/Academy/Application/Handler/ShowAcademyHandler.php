<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Query\ShowAcademyQuery;
use App\Modules\Academy\Application\Response\AcademyResponse;
use App\Modules\Academy\Domain\Academy\Academy;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class ShowAcademyHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    public function __invoke(ShowAcademyQuery $query): AcademyResponse
    {
        $academy = $this->requireAcademy($query->academyId);

        return AcademyResponse::fromAcademy($academy);
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
