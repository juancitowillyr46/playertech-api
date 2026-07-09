<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\Handler;

use App\Modules\Academy\Application\Query\ListAcademiesQuery;
use App\Modules\Academy\Application\Response\AcademyListItemResponse;
use App\Modules\Academy\Domain\Academy\AcademyRepository;
use App\Shared\Application\Pagination\PaginatedResult;

final readonly class ListAcademiesHandler
{
    public function __construct(
        private AcademyRepository $academyRepository,
    ) {
    }

    /**
     * @return AcademyListItemResponse[]
     */
    public function __invoke(ListAcademiesQuery $query): PaginatedResult
    {
        $academies = $this->academyRepository->findAllOrdered(
            $query->pagination
        );

        $items = array_map(
            static fn ($academy): AcademyListItemResponse => AcademyListItemResponse::fromAcademy($academy),
            $academies['items']
        );

        return PaginatedResult::fromItems($items, $query->pagination, $academies['total']);
    }
}
