<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Query\ListAcademiesQuery;
use App\Modules\Academy\Application\Response\AcademyView;
use App\Modules\Academy\Domain\Academy\AcademyRepository;

final readonly class ListAcademiesHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    /**
     * @return AcademyView[]
     */
    public function __invoke(ListAcademiesQuery $query): array
    {
        return array_map(
            static fn ($academy): AcademyView => AcademyView::fromAcademy($academy),
            $this->academyRepository->findAllOrdered()
        );
    }
}
