<?php
declare(strict_types=1);
namespace App\Modules\Charge\Application\Handler;
use App\Modules\Academy\Domain\Academy\AcademyId;
use App\Modules\Charge\Application\Query\ListPendingChargesQuery;
use App\Modules\Charge\Application\Response\ChargeResponse;
use App\Modules\Charge\Domain\Charge\ChargeRepository;
final readonly class ListPendingChargesHandler
{
    public function __construct(private ChargeRepository $chargeRepository) {}
    /** @return ChargeResponse[] */
    public function __invoke(ListPendingChargesQuery $query): array
    {
        return array_map(static fn ($charge) => ChargeResponse::fromCharge($charge), $this->chargeRepository->findPendingByAcademy(new AcademyId($query->academyId)));
    }
}
