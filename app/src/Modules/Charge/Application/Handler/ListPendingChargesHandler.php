<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Application\Query\ListPendingChargesQuery;
use App\Modules\Charge\Application\Response\ChargeResponse;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
use App\Shared\Application\Pagination\PaginatedResult;
final readonly class ListPendingChargesHandler
{
    public function __construct(private ChargeRepository $chargeRepository) {}
    public function __invoke(ListPendingChargesQuery $query): PaginatedResult
    {
        $result = $this->chargeRepository->findPendingByAcademy(new AcademyId($query->academyId), $query->pagination);
        $items = array_map(static fn ($charge) => ChargeResponse::fromCharge($charge), $result['items']);
        return PaginatedResult::fromItems($items, $query->pagination, $result['total']);
    }
}
