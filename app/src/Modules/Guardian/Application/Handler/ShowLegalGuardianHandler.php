<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Guardian\Application\Query\ShowLegalGuardianQuery;
use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\Exception\LegalGuardianNotFoundException;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;

final readonly class ShowLegalGuardianHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    public function __invoke(ShowLegalGuardianQuery $query): LegalGuardianResponse
    {
        $guardian = $this->guardianRepository->findById($query->academyId, $query->guardianId);

        if (null === $guardian) {
            throw new LegalGuardianNotFoundException();
        }

        return LegalGuardianResponse::fromLegalGuardian($guardian);
    }
}
