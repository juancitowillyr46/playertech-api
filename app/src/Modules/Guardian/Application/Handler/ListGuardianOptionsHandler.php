<?php

declare(strict_types=1);

namespace App\Modules\Guardian\Application\Handler;

use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Guardian\Application\Query\ListGuardianOptionsQuery;
use App\Modules\Guardian\Application\Response\GuardianOptionResponse;
use App\Modules\Guardian\Domain\LegalGuardian\LegalGuardianRepository;
use App\Shared\Application\Pagination\PaginationQuery;

final readonly class ListGuardianOptionsHandler
{
    public function __construct(
        private LegalGuardianRepository $guardianRepository,
    ) {
    }

    /**
     * @return GuardianOptionResponse[]
     */
    public function __invoke(ListGuardianOptionsQuery $query): array
    {
        $criteria = trim((string) $query->query);
        $guardians = $this->guardianRepository->findAllByAcademy(
            $query->academyId,
            new PaginationQuery(1, 20, 'first_name', 'ASC'),
            null,
            null,
            '' === $criteria ? null : $criteria,
            null,
            '' === $criteria ? null : $criteria,
        );

        return array_values(array_map(
            static fn ($guardian): GuardianOptionResponse => GuardianOptionResponse::fromGuardian($guardian),
            $guardians['items']
        ));
    }
}
