<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Guardian\Application\Query\ListLegalGuardiansQuery;
use App\Modules\Guardian\Application\Response\LegalGuardianResponse;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListLegalGuardiansHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    public function __invoke(ListLegalGuardiansQuery $query): PaginatedResult
    {
        $guardians = $this->guardianRepository->findAllByAcademy($query->academyId, $query->pagination);

        $items = array_map(
            static fn ($guardian): LegalGuardianResponse => LegalGuardianResponse::fromLegalGuardian($guardian),
            $guardians['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $guardians['total']);
    }
}
